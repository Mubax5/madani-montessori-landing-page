<?php

namespace Tests\Feature;

use App\Models\AdminUser;
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
        foreach (['/', '/tentang', '/program-sekolah', '/program-unggulan', '/bimbel', '/training-parenting', '/galeri', '/kontak'] as $path) {
            $this->get($path)
                ->assertOk()
                ->assertSee('Madani Montessori', false);
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
