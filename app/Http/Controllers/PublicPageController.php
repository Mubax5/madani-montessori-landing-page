<?php

namespace App\Http\Controllers;

use App\Models\BimbelPackage;
use App\Models\Faq;
use App\Models\FeaturedProgram;
use App\Models\GalleryItem;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\Program;
use App\Support\SiteContent;
use Illuminate\Contracts\View\View;

class PublicPageController extends Controller
{
    public function home(): View
    {
        return $this->show('home');
    }

    public function show(string $slug): View
    {
        $page = Page::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->with(['activeSections.image'])
            ->firstOrFail();

        $sections = $page->activeSections->keyBy('section_key');

        return view('public.show', [
            'page' => $page,
            'sections' => $sections,
            'settings' => SiteContent::settings(),
            'headerNavigation' => $this->navigation('header'),
            'footerNavigation' => $this->navigation('footer'),
            'programs' => Program::active()->get(),
            'featuredPrograms' => FeaturedProgram::active()->get(),
            'bimbelPackages' => BimbelPackage::active()->with('items')->get(),
            'galleryItems' => GalleryItem::active()->with('media')->take($slug === 'galeri' ? 12 : 6)->get(),
            'faqs' => Faq::active()->where('page_scope', $slug)->get(),
            'whatsappUrl' => SiteContent::whatsappUrl(match ($slug) {
                'program-sekolah' => 'minat_program_sekolah',
                'bimbel' => 'minat_bimbel',
                'agenda' => 'minat_agenda',
                default => 'konsultasi_umum',
            }),
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
