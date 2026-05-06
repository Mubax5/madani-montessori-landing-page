<?php

namespace Tests\Feature;

use App\Filament\Resources\AdminUsers\AdminUserResource;
use App\Filament\Resources\AuditLogs\AuditLogResource;
use App\Filament\Resources\Leads\LeadResource;
use App\Models\AdminUser;
use App\Models\Lead;
use App\Models\MediaAsset;
use App\Models\Role;
use App\Support\MediaUrl;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use RuntimeException;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_default_admin_password_is_not_created_without_env_in_production(): void
    {
        putenv('ADMIN_INITIAL_PASSWORD');
        unset($_ENV['ADMIN_INITIAL_PASSWORD'], $_SERVER['ADMIN_INITIAL_PASSWORD']);
        $this->app->detectEnvironment(fn (): string => 'production');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('ADMIN_INITIAL_PASSWORD must be set');

        app(DatabaseSeeder::class)->run();
    }

    public function test_inactive_admin_cannot_access_panel(): void
    {
        app(DatabaseSeeder::class)->run();

        $admin = AdminUser::query()->where('email', 'admin@madanimontessori.sch.id')->firstOrFail();
        $admin->forceFill(['is_active' => false])->save();

        $this->actingAs($admin, 'admin')
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_production_admin_without_mfa_is_redirected_to_setup(): void
    {
        app(DatabaseSeeder::class)->run();
        $this->app->detectEnvironment(fn (): string => 'production');
        config(['app.env' => 'production']);

        $admin = AdminUser::query()->where('email', 'admin@madanimontessori.sch.id')->firstOrFail();

        $response = $this->actingAs($admin, 'admin')->get('/admin');

        $response->assertRedirect();
        $this->assertStringContainsString('/admin/multi-factor-authentication/set-up', $response->headers->get('Location'));
    }

    public function test_admin_ip_allowlist_blocks_unlisted_ip(): void
    {
        config(['security.admin_allowed_ips' => ['203.0.113.10']]);
        $this->seed(DatabaseSeeder::class);

        $admin = AdminUser::query()->where('email', 'admin@madanimontessori.sch.id')->firstOrFail();

        $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.10'])
            ->actingAs($admin, 'admin')
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_security_headers_are_sent_on_homepage(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->get('/')
            ->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()')
            ->assertHeader('Content-Security-Policy');
    }

    public function test_http_external_media_url_is_rejected(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertNull(MediaUrl::normalizeManualUrl('http://madanimontessori.online/media.webp'));

        $asset = MediaAsset::query()->create([
            'file_name' => 'bad.webp',
            'file_path' => 'http://madanimontessori.online/bad.webp',
            'mime_type' => 'image/webp',
            'alt_text' => 'Bad URL',
        ]);

        $this->assertNull($asset->fresh()->file_path);
        $this->assertNull($asset->fresh()->file_url);
    }

    public function test_key_role_authorization_rules_hold(): void
    {
        $this->seed(DatabaseSeeder::class);

        $lead = Lead::query()->create([
            'parent_name' => 'Bunda Aisyah',
            'child_name' => 'Alya',
            'child_age' => 5,
            'selected_program' => 'TK A',
            'whatsapp_number' => '081234567890',
            'status' => 'baru',
        ]);

        $viewer = $this->adminForRole('viewer');
        Auth::guard('admin')->login($viewer);

        $this->assertTrue(LeadResource::canAccess());
        $this->assertFalse(LeadResource::canEdit($lead));
        $this->assertFalse(LeadResource::canDelete($lead));
        $this->assertFalse(AdminUserResource::canAccess());
        $this->assertFalse(AuditLogResource::canAccess());

        Auth::guard('admin')->logout();

        $registrationAdmin = $this->adminForRole('admin_pendaftaran');
        Auth::guard('admin')->login($registrationAdmin);

        $this->assertTrue(LeadResource::canAccess());
        $this->assertTrue(LeadResource::canEdit($lead));
        $this->assertFalse(LeadResource::canDelete($lead));
        $this->assertFalse(AdminUserResource::canAccess());
    }

    private function adminForRole(string $roleSlug): AdminUser
    {
        return AdminUser::query()->create([
            'role_id' => Role::query()->where('slug', $roleSlug)->firstOrFail()->id,
            'name' => 'Admin '.$roleSlug,
            'email' => $roleSlug.'@example.test',
            'password_hash' => bcrypt('StrongPassword123!'),
            'is_active' => true,
        ]);
    }
}
