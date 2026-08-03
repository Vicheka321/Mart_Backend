<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = [

        'user_id',

        'action',

        'module',

        'record_id',

        'record_name',

        'description',

        'old_values',

        'new_values',

        'method',

        'url',

        'ip_address',

        'user_agent',

        'device',

        'browser',

        'platform',

        'country',

        'city',

        'status',

    ];

    protected $casts = [

        'old_values' => 'array',

        'new_values' => 'array',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeWarning($query)
    {
        return $query->where('status', 'warning');
    }

    public function scopeModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getActionColorAttribute(): string
    {
        return match ($this->action) {

            'create' => 'green',

            'update' => 'blue',

            'delete' => 'red',

            'login' => 'emerald',

            'logout' => 'gray',

            'restore' => 'yellow',

            'approve' => 'indigo',

            'reject' => 'rose',

            default => 'slate',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {

            'success' => 'green',

            'failed' => 'red',

            'warning' => 'yellow',

            default => 'gray',
        };
    }

    public function getUserNameAttribute(): string
    {
        return $this->user?->name ?? 'System';
    }

    public function getModuleIconAttribute(): string
    {
        return match ($this->module) {

            'Products' => '📦',

            'Orders' => '🛒',

            'Users' => '👤',

            'Payments' => '💳',

            'Categories' => '📂',

            'Brands' => '🏷️',

            'Branches' => '🏪',

            'Delivery Fees' => '🚚',

            'Coupons' => '🎟️',

            'Promotions' => '🔥',

            'Settings' => '⚙️',

            'Authentication' => '🔐',

            default => '📄',
        };
    }
}
