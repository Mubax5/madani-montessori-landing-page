<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\Agenda;
use App\Models\AgendaCategory;
use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicWebsiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->seed();
    }

    public function test_public_pages_render_seeded_content(): void
    {
        foreach (['/', '/tentang', '/program-sekolah', '/program-unggulan', '/bimbel', '/agenda', '/galeri', '/kontak'] as $path) {
            $this->get($path)
                ->assertOk()
                ->assertSee('Madani Montessori', false);
        }

        $this->get('/training-parenting')
            ->assertMovedPermanently()
            ->assertRedirect('/agenda');
    }

    public function test_agenda_detail_and_registration_form_work(): void
    {
        $agenda = Agenda::query()->where('registration_type', 'form')->firstOrFail();

        $this->get(route('agenda.show', $agenda->slug))
            ->assertOk()
            ->assertSee($agenda->title, false);

        $this->post(route('agenda.registrations.store', $agenda->slug), [
            'parent_name' => 'Bunda Aisyah',
            'child_name' => 'Alya',
            'child_age' => 5,
            'whatsapp_number' => '081234567890',
            'email' => 'aisyah@example.com',
            'participant_count' => 2,
            'note' => 'Ikut bersama ayah.',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('agenda_registrations', [
            'agenda_id' => $agenda->id,
            'parent_name' => 'Bunda Aisyah',
            'status' => 'new',
        ]);
    }

    public function test_agenda_filter_uses_active_database_categories(): void
    {
        $category = AgendaCategory::query()->create([
            'name' => 'Seminar Orang Tua',
            'slug' => 'seminar-orang-tua',
            'description' => 'Sesi belajar bersama orang tua.',
            'sort_order' => 99,
            'is_active' => true,
        ]);

        Agenda::query()->create([
            'agenda_category_id' => $category->id,
            'title' => 'Seminar Orang Tua Madani',
            'slug' => 'seminar-orang-tua-madani',
            'excerpt' => 'Sesi diskusi keluarga Madani.',
            'start_at' => now()->addDays(10),
            'registration_type' => 'whatsapp',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->get(route('agenda.index', ['category' => $category->slug]))
            ->assertOk()
            ->assertSee('Seminar Orang Tua', false)
            ->assertSee('Seminar Orang Tua Madani', false);
    }

    public function test_agenda_cover_image_url_takes_priority_over_uploaded_path(): void
    {
        $agenda = Agenda::query()->create([
            'title' => 'Open House Cover URL',
            'slug' => 'open-house-cover-url',
            'excerpt' => 'Agenda dengan gambar URL.',
            'cover_image_path' => 'agendas/uploaded-cover.webp',
            'cover_image_url' => 'https://example.com/cover-agenda.webp',
            'start_at' => now()->addDays(12),
            'registration_type' => 'whatsapp',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->get(route('agenda.show', $agenda->slug))
            ->assertOk()
            ->assertSee('https://example.com/cover-agenda.webp', false)
            ->assertDontSee('agendas/uploaded-cover.webp', false);
    }

    public function test_public_pages_do_not_show_internal_terms(): void
    {
        foreach (['/', '/tentang', '/program-sekolah', '/program-unggulan', '/bimbel', '/agenda', '/galeri', '/kontak'] as $path) {
            $this->get($path)
                ->assertOk()
                ->assertDontSeeText('CMS')
                ->assertDontSeeText('Admin')
                ->assertDontSeeText('admin')
                ->assertDontSeeText('published')
                ->assertDontSeeText('testing')
                ->assertDontSeeText('dummy')
                ->assertDontSeeText('seeder')
                ->assertDontSeeText('object storage')
                ->assertDontSeeText('Featured Agenda')
                ->assertDontSeeText('Agenda aktif')
                ->assertDontSeeText('Hubungi Admin');
        }
    }

    public function test_registration_form_stores_a_new_lead(): void
    {
        $this->post(route('leads.store'), [
            'parent_name' => 'Bunda Aisyah',
            'child_name' => 'Alya',
            'child_age' => 5,
            'selected_program' => 'TK A',
            'whatsapp_number' => '081234567890',
            'note' => 'Ingin konsultasi jadwal.',
            'source_page' => 'kontak',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('leads', [
            'parent_name' => 'Bunda Aisyah',
            'child_name' => 'Alya',
            'status' => 'baru',
        ]);
    }

    public function test_admin_panel_requires_admin_authentication(): void
    {
        $this->get('/admin')->assertRedirect();

        $admin = AdminUser::query()->where('email', 'admin@madanimontessori.sch.id')->firstOrFail();

        $this->actingAs($admin, 'admin')
            ->get('/admin')
            ->assertOk();
    }
}
