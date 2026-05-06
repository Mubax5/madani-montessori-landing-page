<?php

namespace App\Console\Commands;

use App\Models\AdminUser;
use App\Support\AuditLogger;
use App\Support\Security\AdminPassword;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RotateAdminPasswordCommand extends Command
{
    protected $signature = 'admin:rotate-password {email} {--password=}';

    protected $description = 'Rotate an admin CMS password without storing credentials in code.';

    public function handle(): int
    {
        $user = AdminUser::query()
            ->where('email', (string) $this->argument('email'))
            ->first();

        if (! $user) {
            $this->error('Admin user not found.');

            return self::FAILURE;
        }

        $password = (string) ($this->option('password') ?: Str::password(32));

        AdminPassword::assertValid($password);

        $user->forceFill([
            'password_hash' => Hash::make($password),
        ])->save();

        AuditLogger::write(
            action: 'password_rotated',
            module: 'admin_users',
            recordId: $user->id,
            newData: ['email' => $user->email],
        );

        $this->info('Admin password rotated.');

        if (! $this->option('password')) {
            $this->warn('Generated one-time password: '.$password);
            $this->warn('Store it securely and change it after login.');
        }

        return self::SUCCESS;
    }
}
