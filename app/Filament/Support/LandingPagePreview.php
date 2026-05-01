<?php

namespace App\Filament\Support;

use App\Models\BimbelPackage;
use App\Models\BimbelPackageItem;
use App\Models\Faq;
use App\Models\FeaturedProgram;
use App\Models\GalleryItem;
use App\Models\Lead;
use App\Models\MediaAsset;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\Program;
use App\Models\SiteSetting;
use App\Models\TrainingEvent;
use App\Models\WhatsappTemplate;
use Filament\Actions\Action;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class LandingPagePreview
{
    public static function action(?string $fallbackSlug = null): Action
    {
        return Action::make('previewLandingPage')
            ->label('Preview')
            ->icon(Heroicon::OutlinedEye)
            ->color('gray')
            ->modalHeading(fn (Model $record): string => 'Preview Landing Page: ' . static::title($record, $fallbackSlug))
            ->modalDescription('Preview ini menampilkan halaman publik asli dengan ukuran desktop, tablet, dan mobile.')
            ->modalWidth('7xl')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Tutup')
            ->modalContent(fn (Model $record): HtmlString => static::viewForRecord($record, $fallbackSlug, 'modal'));
    }

    public static function formPreview(?string $fallbackSlug = null): Html
    {
        return Html::make(fn (?Model $record): HtmlString => $record
            ? static::viewForRecord($record, $fallbackSlug, 'form')
            : new HtmlString('<div class="madani-preview-empty">Preview tersedia setelah data pertama kali disimpan.</div>'))
            ->columnSpanFull();
    }

    public static function mediaThumbnail(string $statePath): Html
    {
        return Html::make(function (Get $get) use ($statePath): HtmlString {
            $mediaId = $get($statePath);
            $media = filled($mediaId) ? MediaAsset::query()->find($mediaId) : null;

            return new HtmlString(view('filament.components.media-thumbnail', [
                'media' => $media,
            ])->render());
        })->columnSpanFull();
    }

    public static function mediaOption(MediaAsset $media): string
    {
        return view('filament.components.media-option', [
            'media' => $media,
        ])->render();
    }

    public static function viewForRecord(Model $record, ?string $fallbackSlug = null, string $variant = 'modal'): HtmlString
    {
        $slug = static::slug($record, $fallbackSlug);
        $url = static::url($slug);

        return new HtmlString(view('filament.components.landing-preview', [
            'title' => static::title($record, $fallbackSlug),
            'url' => $url,
            'variant' => $variant,
        ])->render());
    }

    public static function url(?string $slug): string
    {
        $slug = trim((string) $slug, '/');

        return match ($slug) {
            '', 'home' => route('home'),
            'tentang' => route('tentang'),
            'program-sekolah' => route('program-sekolah'),
            'program-unggulan' => route('program-unggulan'),
            'bimbel' => route('bimbel'),
            'training-parenting' => route('training-parenting'),
            'galeri' => route('galeri'),
            'kontak' => route('kontak'),
            default => url($slug),
        };
    }

    public static function slug(?Model $record, ?string $fallbackSlug = null): string
    {
        if (! $record) {
            return $fallbackSlug ?? 'home';
        }

        return match (true) {
            $record instanceof Page => $record->slug,
            $record instanceof PageSection => $record->page?->slug ?? $fallbackSlug ?? 'home',
            $record instanceof Program => 'program-sekolah',
            $record instanceof FeaturedProgram => 'program-unggulan',
            $record instanceof BimbelPackage, $record instanceof BimbelPackageItem => 'bimbel',
            $record instanceof TrainingEvent => 'training-parenting',
            $record instanceof GalleryItem, $record instanceof MediaAsset => 'galeri',
            $record instanceof Faq => $record->page_scope,
            $record instanceof Lead => $record->source_page ?: 'kontak',
            $record instanceof NavigationItem => static::slugFromUrl($record->url),
            $record instanceof SiteSetting => static::slugFromSetting($record->setting_key),
            $record instanceof WhatsappTemplate => static::slugFromWhatsappTemplate($record->template_key),
            default => $fallbackSlug ?? 'home',
        };
    }

    public static function title(?Model $record, ?string $fallbackSlug = null): string
    {
        $slug = static::slug($record, $fallbackSlug);

        return static::pageLabels()[$slug] ?? str($slug)->replace('-', ' ')->title()->toString();
    }

    public static function pageLabels(): array
    {
        return [
            'home' => 'Beranda',
            'tentang' => 'Tentang',
            'program-sekolah' => 'Program Sekolah',
            'program-unggulan' => 'Program Unggulan',
            'bimbel' => 'Bimbel',
            'training-parenting' => 'Training & Parenting',
            'galeri' => 'Galeri',
            'kontak' => 'Kontak',
        ];
    }

    private static function slugFromUrl(?string $url): string
    {
        $path = trim(parse_url((string) $url, PHP_URL_PATH) ?: (string) $url, '/');

        return $path === '' ? 'home' : $path;
    }

    private static function slugFromSetting(?string $key): string
    {
        return match (true) {
            str_contains((string) $key, 'maps'),
            str_contains((string) $key, 'address'),
            str_contains((string) $key, 'whatsapp'),
            str_contains((string) $key, 'email') => 'kontak',
            str_contains((string) $key, 'footer') => 'home',
            default => 'home',
        };
    }

    private static function slugFromWhatsappTemplate(?string $key): string
    {
        return match ($key) {
            'minat_program_sekolah' => 'program-sekolah',
            'minat_bimbel' => 'bimbel',
            'minat_training_parenting' => 'training-parenting',
            default => 'kontak',
        };
    }
}
