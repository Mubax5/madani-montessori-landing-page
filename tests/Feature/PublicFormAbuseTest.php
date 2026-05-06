<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Support\PublicFormAbuseGuard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class PublicFormAbuseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        RateLimiter::clear('public-form:lead:127.0.0.1:'.hash('sha256', '6281234567890'));
        $this->seed();
    }

    public function test_lead_form_accepts_valid_submission(): void
    {
        $this->post(route('leads.store'), $this->validLeadPayload())
            ->assertSessionHas('success');

        $this->assertDatabaseHas('leads', [
            'parent_name' => 'Bunda Aisyah',
            'whatsapp_normalized' => '6281234567890',
        ]);
    }

    public function test_lead_form_rejects_invalid_phone(): void
    {
        $payload = $this->validLeadPayload(['whatsapp_number' => '12345']);

        $this->post(route('leads.store'), $payload)
            ->assertSessionHasErrors('whatsapp_number');
    }

    public function test_lead_form_honeypot_returns_success_without_saving(): void
    {
        $payload = $this->validLeadPayload([
            PublicFormAbuseGuard::honeypotField() => 'https://spam.example',
        ]);

        $this->post(route('leads.store'), $payload)
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('leads', [
            'parent_name' => 'Bunda Aisyah',
        ]);
    }

    public function test_lead_form_limits_repeated_submissions_by_number(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $this->post(route('leads.store'), $this->validLeadPayload([
                'parent_name' => 'Bunda Aisyah '.$i,
            ]))->assertSessionHas('success');
        }

        $this->post(route('leads.store'), $this->validLeadPayload([
            'parent_name' => 'Bunda Aisyah 4',
        ]))->assertStatus(429);
    }

    public function test_agenda_registration_honeypot_returns_success_without_saving(): void
    {
        $agenda = Agenda::query()->where('registration_type', 'form')->firstOrFail();

        $this->post(route('agenda.registrations.store', $agenda->slug), [
            'parent_name' => 'Bunda Aisyah',
            'child_name' => 'Alya',
            'child_age' => 5,
            'whatsapp_number' => '081234567890',
            'email' => 'aisyah@example.com',
            'participant_count' => 2,
            'note' => 'Ikut bersama ayah.',
            PublicFormAbuseGuard::honeypotField() => 'spam',
        ])->assertSessionHas('success');

        $this->assertDatabaseMissing('agenda_registrations', [
            'parent_name' => 'Bunda Aisyah',
        ]);
    }

    private function validLeadPayload(array $overrides = []): array
    {
        return array_merge([
            'parent_name' => 'Bunda Aisyah',
            'child_name' => 'Alya',
            'child_age' => 5,
            'selected_program' => 'TK A',
            'whatsapp_number' => '081234567890',
            'note' => 'Ingin konsultasi jadwal.',
            'source_page' => 'kontak',
        ], $overrides);
    }
}
