<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agenda_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->string('slug', 140)->unique();
            $table->text('description')->nullable();
            $table->string('color', 40)->nullable();
            $table->string('icon', 80)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('agendas', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('agenda_category_id')->nullable()->constrained('agenda_categories')->nullOnDelete();
            $table->string('title', 180);
            $table->string('slug', 200)->unique();
            $table->text('excerpt')->nullable();
            $table->longText('description')->nullable();
            $table->string('cover_image_path')->nullable();
            $table->string('location_name')->nullable();
            $table->text('location_address')->nullable();
            $table->string('maps_url', 2048)->nullable();
            $table->dateTime('start_at')->nullable()->index();
            $table->dateTime('end_at')->nullable();
            $table->dateTime('registration_start_at')->nullable();
            $table->dateTime('registration_end_at')->nullable();
            $table->string('target_audience')->nullable();
            $table->unsignedInteger('quota')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->boolean('is_free')->default(true);
            $table->enum('registration_type', ['whatsapp', 'form', 'external_url'])->default('whatsapp');
            $table->string('registration_url', 2048)->nullable();
            $table->text('whatsapp_template')->nullable();
            $table->enum('status', ['draft', 'published', 'closed', 'cancelled', 'archived'])->default('draft')->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->integer('sort_order')->default(0);
            $table->string('meta_title', 180)->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('admin_users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('admin_users')->nullOnDelete();
            $table->timestamps();
            $table->index('agenda_category_id');
        });

        Schema::create('agenda_registrations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('agenda_id')->constrained('agendas')->cascadeOnDelete();
            $table->string('parent_name', 150);
            $table->string('child_name', 150)->nullable();
            $table->unsignedTinyInteger('child_age')->nullable();
            $table->string('whatsapp_number', 30);
            $table->string('email', 150)->nullable();
            $table->unsignedInteger('participant_count')->default(1);
            $table->text('note')->nullable();
            $table->enum('status', ['new', 'contacted', 'confirmed', 'cancelled'])->default('new')->index();
            $table->string('source', 100)->nullable();
            $table->timestamps();
            $table->index('agenda_id');
        });

        $this->seedInitialCategories();
        $this->migrateTrainingEvents();
        $this->migrateCmsContent();
    }

    public function down(): void
    {
        Schema::dropIfExists('agenda_registrations');
        Schema::dropIfExists('agendas');
        Schema::dropIfExists('agenda_categories');
    }

    private function seedInitialCategories(): void
    {
        foreach ($this->initialCategories() as $index => $category) {
            DB::table('agenda_categories')->updateOrInsert(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'color' => $category['color'],
                    'icon' => $category['icon'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );
        }
    }

    private function migrateTrainingEvents(): void
    {
        if (! Schema::hasTable('training_events')) {
            return;
        }

        $categories = DB::table('agenda_categories')->pluck('id', 'slug');

        DB::table('training_events')->orderBy('id')->get()->each(function (object $event) use ($categories): void {
            $categorySlug = Str::contains(Str::lower((string) $event->topic), 'workshop') || $event->target_audience === 'guru'
                ? 'workshop'
                : 'parenting-class';

            [$startAt, $endAt] = $this->dateTimesFromTrainingEvent($event);
            $slug = $this->uniqueAgendaSlug(Str::slug((string) $event->title) ?: 'agenda-training-' . $event->id);
            $status = in_array($event->status, ['draft', 'published', 'closed'], true) ? $event->status : 'draft';

            DB::table('agendas')->insert([
                'agenda_category_id' => $categories[$categorySlug] ?? null,
                'title' => $event->title,
                'slug' => $slug,
                'excerpt' => $event->topic,
                'description' => $event->description,
                'start_at' => $startAt,
                'end_at' => $endAt,
                'target_audience' => $this->targetAudienceLabel((string) $event->target_audience),
                'registration_type' => 'whatsapp',
                'status' => $status,
                'is_featured' => false,
                'sort_order' => (int) $event->sort_order,
                'published_at' => $status === 'published' ? now() : null,
                'created_at' => $event->created_at ?? now(),
                'updated_at' => $event->updated_at ?? now(),
            ]);
        });
    }

    private function migrateCmsContent(): void
    {
        if (Schema::hasTable('navigation_items')) {
            DB::table('navigation_items')
                ->where('url', '/training-parenting')
                ->update([
                    'label' => 'Agenda',
                    'url' => '/agenda',
                    'updated_at' => now(),
                ]);
        }

        if (Schema::hasTable('pages')) {
            DB::table('pages')->updateOrInsert(
                ['slug' => 'agenda'],
                [
                    'title' => 'Agenda',
                    'meta_title' => 'Agenda Madani Montessori',
                    'meta_description' => 'Trial class, study tour, parenting class, workshop, field trip, dan kegiatan sekolah Madani Montessori.',
                    'is_published' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );
        }
    }

    private function dateTimesFromTrainingEvent(object $event): array
    {
        if (blank($event->event_date)) {
            return [null, null];
        }

        $date = Carbon::parse($event->event_date);
        $time = str_replace('.', ':', (string) $event->event_time);

        preg_match_all('/\d{1,2}:\d{2}/', $time, $matches);
        $start = $matches[0][0] ?? '09:00';
        $end = $matches[0][1] ?? null;

        return [
            Carbon::parse($date->toDateString() . ' ' . $start),
            $end ? Carbon::parse($date->toDateString() . ' ' . $end) : null,
        ];
    }

    private function uniqueAgendaSlug(string $base): string
    {
        $slug = $base;
        $suffix = 2;

        while (DB::table('agendas')->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $suffix++;
        }

        return $slug;
    }

    private function targetAudienceLabel(string $target): string
    {
        return match ($target) {
            'guru' => 'Guru',
            'orang_tua' => 'Orang tua',
            default => 'Guru dan orang tua',
        };
    }

    private function initialCategories(): array
    {
        return [
            ['name' => 'Trial Class', 'slug' => 'trial-class', 'description' => 'Kelas percobaan untuk calon siswa.', 'color' => '#F5C542', 'icon' => 'academic-cap'],
            ['name' => 'Study Tour', 'slug' => 'study-tour', 'description' => 'Kunjungan belajar di luar sekolah.', 'color' => '#1D4ED8', 'icon' => 'map'],
            ['name' => 'Open House', 'slug' => 'open-house', 'description' => 'Sesi kunjungan dan pengenalan sekolah.', 'color' => '#0A1F5C', 'icon' => 'home'],
            ['name' => 'Parenting Class', 'slug' => 'parenting-class', 'description' => 'Kelas parenting untuk orang tua.', 'color' => '#0F766E', 'icon' => 'users'],
            ['name' => 'Workshop', 'slug' => 'workshop', 'description' => 'Workshop guru dan komunitas pendidikan.', 'color' => '#7C3AED', 'icon' => 'sparkles'],
            ['name' => 'Field Trip', 'slug' => 'field-trip', 'description' => 'Kegiatan eksplorasi dan perjalanan edukatif.', 'color' => '#EA580C', 'icon' => 'flag'],
            ['name' => 'Event Sekolah', 'slug' => 'event-sekolah', 'description' => 'Agenda dan perayaan sekolah.', 'color' => '#DB2777', 'icon' => 'calendar-days'],
            ['name' => 'Bimbel', 'slug' => 'bimbel', 'description' => 'Kegiatan bimbel Madani.', 'color' => '#2563EB', 'icon' => 'book-open'],
        ];
    }
};
