<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    /**
     * Create audit log
     */
    public static function log(
        string $action,
        string $module,
        ?Model $model = null,
        ?array $old = null,
        ?array $new = null,
        ?string $description = null,
        string $status = 'success'
    ): void {

        AuditLog::create([

            'user_id' => Auth::id(),

            'action' => strtolower($action),

            'module' => $module,

            'record_id' => $model?->getKey(),

            'record_name' => self::recordName($model),

            'description' => $description,

            'old_values' => $old,

            'new_values' => $new,

            'method' => request()->method(),

            'url' => request()->fullUrl(),

            'ip_address' => request()->ip(),

            'user_agent' => request()->userAgent(),

            'device' => self::device(),

            'browser' => self::browser(),

            'platform' => self::platform(),

            'status' => $status,

        ]);
    }

    /**
     * Create Log
     */
    public static function created(
        string $module,
        Model $model,
        ?string $description = null
    ): void {

        self::log(
            action: 'create',
            module: $module,
            model: $model,
            old: null,
            new: $model->toArray(),
            description: $description
        );
    }

    /**
     * Update Log
     */
    public static function updated(
        string $module,
        Model $model,
        array $old,
        ?string $description = null
    ): void {

        self::log(
            action: 'update',
            module: $module,
            model: $model,
            old: $old,
            new: $model->fresh()->toArray(),
            description: $description
        );
    }

    /**
     * Delete Log
     */
    public static function deleted(
        string $module,
        Model $model,
        ?string $description = null
    ): void {

        self::log(
            action: 'delete',
            module: $module,
            model: $model,
            old: $model->toArray(),
            new: null,
            description: $description
        );
    }

    /**
     * Login Log
     */
    public static function login(): void
    {
        self::log(
            action: 'login',
            module: 'Authentication',
            description: 'User logged in.'
        );
    }

    /**
     * Logout Log
     */
    public static function logout(): void
    {
        self::log(
            action: 'logout',
            module: 'Authentication',
            description: 'User logged out.'
        );
    }

    /**
     * Failed Action
     */
    public static function failed(
        string $action,
        string $module,
        ?string $description = null
    ): void {

        self::log(
            action: $action,
            module: $module,
            description: $description,
            status: 'failed'
        );
    }

    /**
     * Record Name
     */
    private static function recordName(?Model $model): ?string
    {
        if (!$model) {
            return null;
        }

        foreach (
            [
                'name',
                'title',
                'code',
                'email',
            ] as $column
        ) {

            if (isset($model->{$column})) {
                return $model->{$column};
            }
        }

        return '#' . $model->getKey();
    }

    /**
     * Browser
     */
    private static function browser(): string
    {
        $agent = request()->userAgent() ?? '';

        if (str_contains($agent, 'Edg')) {
            return 'Edge';
        }

        if (str_contains($agent, 'Firefox')) {
            return 'Firefox';
        }

        if (str_contains($agent, 'Chrome')) {
            return 'Chrome';
        }

        if (str_contains($agent, 'Safari')) {
            return 'Safari';
        }

        return 'Unknown';
    }

    /**
     * Platform
     */
    private static function platform(): string
    {
        $agent = request()->userAgent() ?? '';

        if (str_contains($agent, 'Windows')) {
            return 'Windows';
        }

        if (str_contains($agent, 'Mac')) {
            return 'MacOS';
        }

        if (str_contains($agent, 'Android')) {
            return 'Android';
        }

        if (str_contains($agent, 'iPhone')) {
            return 'iOS';
        }

        if (str_contains($agent, 'Linux')) {
            return 'Linux';
        }

        return 'Unknown';
    }

    /**
     * Device
     */
    private static function device(): string
    {
        $agent = request()->userAgent() ?? '';

        if (
            str_contains($agent, 'Mobile') ||
            str_contains($agent, 'Android') ||
            str_contains($agent, 'iPhone')
        ) {
            return 'Mobile';
        }

        return 'Desktop';
    }
}
