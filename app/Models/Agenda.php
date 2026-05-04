<?php

namespace App\Models;

use App\Models\Concerns\HasFileUrls;
use App\Support\MediaUrl;
use App\Support\SiteContent;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Agenda extends Model
{
    use HasFileUrls;

    protected $fillable = [
        'agenda_category_id',
        'title',
        'slug',
        'excerpt',
        'description',
        'cover_image_path',
        'cover_image_url',
        'location_name',
        'location_address',
        'maps_url',
        'start_at',
        'end_at',
        'registration_start_at',
        'registration_end_at',
        'target_audience',
        'quota',
        'price',
        'is_free',
        'registration_type',
        'registration_url',
        'whatsapp_template',
        'status',
        'is_featured',
        'sort_order',
        'meta_title',
        'meta_description',
        'published_at',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'registration_start_at' => 'datetime',
            'registration_end_at' => 'datetime',
            'published_at' => 'datetime',
            'is_free' => 'boolean',
            'is_featured' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(AgendaCategory::class, 'agenda_category_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(AgendaRegistration::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'updated_by');
    }

    public function scopePublished($query)
    {
        return $query
            ->where('status', 'published')
            ->where(function ($query): void {
                $query->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function scopeUpcoming($query)
    {
        return $query->whereNotNull('start_at')->where('start_at', '>=', now()->startOfDay());
    }

    public function scopePast($query)
    {
        return $query->whereNotNull('start_at')->where('start_at', '<', now()->startOfDay());
    }

    protected function coverImageFinalUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->resolveFileUrl(
            path: $this->cover_image_path,
            manualUrl: $this->cover_image_url,
        ));
    }

    protected function coverUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->cover_image_final_url);
    }

    public function getDateLabelAttribute(): string
    {
        if (! $this->start_at) {
            return 'Tanggal menyusul';
        }

        if ($this->end_at && $this->end_at->toDateString() !== $this->start_at->toDateString()) {
            return $this->start_at->translatedFormat('d M Y').' - '.$this->end_at->translatedFormat('d M Y');
        }

        return $this->start_at->translatedFormat('d M Y');
    }

    public function getTimeLabelAttribute(): string
    {
        if (! $this->start_at) {
            return 'Jam menyusul';
        }

        if ($this->end_at) {
            return $this->start_at->format('H:i').' - '.$this->end_at->format('H:i');
        }

        return $this->start_at->format('H:i');
    }

    public function getFormattedPriceAttribute(): string
    {
        if ($this->is_free || blank($this->price)) {
            return 'Gratis';
        }

        return 'Rp '.number_format((float) $this->price, 0, ',', '.');
    }

    public function registrationStatusLabel(): string
    {
        if (in_array($this->status, ['closed', 'cancelled', 'archived'], true)) {
            return match ($this->status) {
                'cancelled' => 'Dibatalkan',
                'archived' => 'Diarsipkan',
                default => 'Pendaftaran ditutup',
            };
        }

        if ($this->registration_start_at && $this->registration_start_at->isFuture()) {
            return 'Pendaftaran segera dibuka';
        }

        if ($this->registration_end_at && $this->registration_end_at->isPast()) {
            return 'Pendaftaran ditutup';
        }

        return 'Pendaftaran dibuka';
    }

    public function isRegistrationOpen(): bool
    {
        if ($this->status !== 'published') {
            return false;
        }

        if ($this->registration_start_at && $this->registration_start_at->isFuture()) {
            return false;
        }

        if ($this->registration_end_at && $this->registration_end_at->isPast()) {
            return false;
        }

        return true;
    }

    public function registrationCtaUrl(): string
    {
        if ($this->registration_type === 'external_url' && filled($this->registration_url)) {
            return $this->registration_url;
        }

        if (filled($this->whatsapp_template)) {
            $phone = preg_replace('/\D+/', '', SiteContent::setting('whatsapp_number', '6282123576275'));
            $message = str_replace(
                ['{agenda}', '{tanggal}', '{lokasi}'],
                [$this->title, $this->date_label, $this->location_name ?: 'Madani Montessori Islamic School'],
                $this->whatsapp_template,
            );

            return 'https://wa.me/'.$phone.'?text='.rawurlencode($message);
        }

        return SiteContent::whatsappUrl('minat_agenda', [
            'agenda' => $this->title,
            'tanggal' => $this->date_label,
            'lokasi' => $this->location_name ?: 'Madani Montessori Islamic School',
        ]);
    }

    protected static function booted(): void
    {
        static::saving(function (Agenda $agenda): void {
            $agenda->cover_image_url = MediaUrl::normalizeManualUrl($agenda->cover_image_url);

            if (MediaUrl::isRemoteUrl($agenda->cover_image_path)) {
                $agenda->cover_image_url ??= $agenda->cover_image_path;
                $agenda->cover_image_path = null;
            } else {
                $agenda->cover_image_path = MediaUrl::isTemporaryPath($agenda->cover_image_path)
                    ? null
                    : MediaUrl::normalizePath($agenda->cover_image_path);
            }

            if (blank($agenda->slug) && filled($agenda->title)) {
                $agenda->slug = Str::slug($agenda->title);
            }

            if ($agenda->status === 'published' && blank($agenda->published_at)) {
                $agenda->published_at = now();
            }
        });
    }
}
