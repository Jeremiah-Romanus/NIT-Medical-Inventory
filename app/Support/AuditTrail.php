<?php

namespace App\Support;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditTrail
{
    public static function record(
        string $action,
        ?Model $auditable = null,
        ?string $subject = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?array $metadata = null
    ): void {
        $request = request();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'auditable_type' => $auditable ? $auditable::class : null,
            'auditable_id' => $auditable?->getKey(),
            'subject' => $subject,
            'old_values' => self::clean($oldValues),
            'new_values' => self::clean($newValues),
            'metadata' => self::clean($metadata),
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }

    protected static function clean(?array $values): ?array
    {
        if ($values === null) {
            return null;
        }

        return collect($values)
            ->reject(fn ($value, $key) => in_array($key, ['password', 'remember_token'], true))
            ->all();
    }
}
