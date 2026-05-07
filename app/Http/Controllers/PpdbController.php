<?php

namespace App\Http\Controllers;

use App\Models\NavigationItem;
use App\Models\Page;
use App\Support\SiteContent;
use Illuminate\Contracts\View\View;

class PpdbController extends Controller
{
    public function index(): View
    {
        return $this->render('index', 'PPDB Online', 'Pendaftaran murid baru Madani Montessori Islamic School.');
    }

    public function create(): View
    {
        return $this->render('form', 'Daftar PPDB Online', 'Form pendaftaran murid baru Madani Montessori Islamic School.');
    }

    public function check(): View
    {
        return $this->render('check', 'Cek Status PPDB', 'Cek status pendaftaran murid baru Madani Montessori Islamic School.');
    }

    private function render(string $view, string $title, string $description): View
    {
        return view('public.ppdb.'.$view, [
            'page' => new Page([
                'slug' => 'ppdb',
                'title' => $title,
                'meta_title' => $title.' - Madani Montessori Islamic School',
                'meta_description' => $description,
                'is_published' => true,
            ]),
            'settings' => SiteContent::settings(),
            'headerNavigation' => $this->navigation('header'),
            'footerNavigation' => $this->navigation('footer'),
            'whatsappUrl' => SiteContent::whatsappUrl('minat_program_sekolah'),
            'apiUrl' => rtrim(config('services.madani_nidham.api_url'), '/'),
            'documentRequirements' => [
                'Akta kelahiran',
                'Kartu keluarga',
                'Pas foto anak',
                'KTP orang tua',
            ],
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
