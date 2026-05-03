<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\Agenda;
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
