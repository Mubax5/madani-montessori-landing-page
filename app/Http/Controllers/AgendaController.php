<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAgendaRegistrationRequest;
use App\Models\Agenda;
use App\Models\AgendaCategory;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Support\SiteContent;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index(Request $request): View
    {
        $selectedCategory = $request->string('category')->toString();
        $categories = AgendaCategory::active()->get();
        $page = $this->page('agenda', 'Agenda', 'Agenda Madani Montessori');

        $published = Agenda::published()
            ->with('category')
            ->when(filled($selectedCategory), fn ($query) => $query->whereHas('category', fn ($query) => $query->where('slug', $selectedCategory)));

        $upcoming = (clone $published)
            ->upcoming()
            ->orderBy('start_at')
            ->orderBy('sort_order')
            ->get();

        $past = (clone $published)
            ->past()
            ->orderByDesc('start_at')
            ->limit(6)
            ->get();

        $featuredAgenda = (clone $published)
            ->where('is_featured', true)
            ->orderBy('start_at')
            ->orderBy('sort_order')
            ->first()
            ?: $upcoming->first();

        return view('public.agenda.index', [
            'page' => $page,
            'settings' => SiteContent::settings(),
            'headerNavigation' => $this->navigation('header'),
            'footerNavigation' => $this->navigation('footer'),
            'whatsappUrl' => SiteContent::whatsappUrl('minat_agenda', [
                'agenda' => 'Agenda Madani Montessori',
                'tanggal' => 'jadwal terdekat',
                'lokasi' => 'Madani Montessori Islamic School',
            ]),
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'featuredAgenda' => $featuredAgenda,
            'upcomingAgendas' => $upcoming,
            'pastAgendas' => $past,
        ]);
    }

    public function show(string $slug): View
    {
        $agenda = Agenda::published()
            ->where('slug', $slug)
            ->with(['category', 'registrations'])
            ->firstOrFail();

        $page = new Page([
            'slug' => 'agenda',
            'title' => $agenda->meta_title ?: $agenda->title,
            'meta_title' => $agenda->meta_title ?: $agenda->title,
            'meta_description' => $agenda->meta_description ?: $agenda->excerpt,
        ]);

        $relatedAgendas = Agenda::published()
            ->with('category')
            ->whereKeyNot($agenda->id)
            ->where(function ($query) use ($agenda): void {
                $query
                    ->when($agenda->agenda_category_id, fn ($query) => $query->where('agenda_category_id', $agenda->agenda_category_id))
                    ->orWhere('start_at', '>=', now()->startOfDay());
            })
            ->orderBy('start_at')
            ->limit(3)
            ->get();

        return view('public.agenda.show', [
            'page' => $page,
            'settings' => SiteContent::settings(),
            'headerNavigation' => $this->navigation('header'),
            'footerNavigation' => $this->navigation('footer'),
            'whatsappUrl' => $agenda->registrationCtaUrl(),
            'agenda' => $agenda,
            'relatedAgendas' => $relatedAgendas,
        ]);
    }

    public function storeRegistration(StoreAgendaRegistrationRequest $request, Agenda $agenda): RedirectResponse
    {
        if ($agenda->registration_type !== 'form' || ! $agenda->isRegistrationOpen()) {
            return back()->withErrors([
                'agenda_registration' => 'Pendaftaran agenda ini sedang tidak dibuka.',
            ]);
        }

        $agenda->registrations()->create($request->validated() + [
            'status' => 'new',
            'source' => 'public_agenda',
        ]);

        return back()->with('success', 'Terima kasih, pendaftaran agenda sudah kami terima.');
    }

    private function page(string $slug, string $title, string $metaTitle): Page
    {
        return Page::query()->where('slug', $slug)->first() ?: new Page([
            'slug' => $slug,
            'title' => $title,
            'meta_title' => $metaTitle,
            'meta_description' => 'Agenda trial class, study tour, parenting class, workshop, dan kegiatan sekolah Madani Montessori.',
        ]);
    }

    private function navigation(string $location)
    {
        return NavigationItem::query()
            ->where('location', $location)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }
}
