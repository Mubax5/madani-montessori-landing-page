<?php

namespace App\Http\Controllers;

use App\Models\BimbelPackage;
use App\Models\Faq;
use App\Models\FeaturedProgram;
use App\Models\GalleryItem;
use App\Models\MediaAsset;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\Program;
use App\Support\SiteContent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

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
            'galleryItems' => $this->galleryItems($slug),
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

    private function galleryItems(string $slug): Collection
    {
        $limit = $slug === 'galeri' ? 12 : 6;
        $items = GalleryItem::active()
            ->with('media')
            ->take($limit)
            ->get();

        $remaining = $limit - $items->count();

        if ($remaining < 1) {
            return $items;
        }

        $fallbackMedia = MediaAsset::query()
            ->whereDoesntHave('galleryItems')
            ->latest()
            ->take($remaining)
            ->get();

        return $items->concat($fallbackMedia->map(fn (MediaAsset $media): GalleryItem => $this->galleryItemFromMedia($media)));
    }

    private function galleryItemFromMedia(MediaAsset $media): GalleryItem
    {
        $item = new GalleryItem([
            'media_id' => $media->id,
            'category' => 'sekolah',
            'title' => $media->alt_text ?: $media->caption ?: pathinfo($media->file_name, PATHINFO_FILENAME),
            'description' => $media->caption,
            'is_featured' => false,
            'sort_order' => 999,
            'is_active' => true,
        ]);

        $item->setRelation('media', $media);

        return $item;
    }
}
