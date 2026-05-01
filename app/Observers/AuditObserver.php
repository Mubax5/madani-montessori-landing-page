<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditObserver
{
    public function created(Model $model): void
    {
        $this->write('created', $model, null, $model->getAttributes());
    }

    public function updated(Model $model): void
    {
        $this->write('updated', $model, $model->getOriginal(), $model->getChanges());
    }

    public function deleted(Model $model): void
    {
        $this->write('deleted', $model, $model->getOriginal(), null);
    }

    private function write(string $action, Model $model, ?array $oldData, ?array $newData): void
    {
        $admin = Auth::guard('admin')->user();

        if (! $admin) {
            return;
        }

        AuditLog::create([
            'admin_user_id' => $admin->id,
            'action' => $action,
            'module' => $model->getTable(),
            'record_id' => $model->getKey(),
            'old_data' => $oldData,
            'new_data' => $newData,
            'ip_address' => request()->ip(),
            'user_agent' => substr((string) request()->userAgent(), 0, 2000),
            'created_at' => now(),
        ]);
    }
}
