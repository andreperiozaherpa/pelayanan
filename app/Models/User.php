<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\AuditLog;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'desa_id',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'desa_id', 'id');
    }

    public function hasPermission(string $permissionSlug): bool
    {
        return $this->role && $this->role->permissions()->where('slug', $permissionSlug)->exists();
    }

    /**
     * Record an audit log for this user.
     */
    public function recordAuditLog(string $action, ?string $table = null, ?int $targetId = null, ?array $oldValue = null, ?array $newValue = null): void
    {
        AuditLog::create([
            'user_id' => $this->id,
            'action' => $action,
            'target_table' => $table,
            'target_id' => $targetId,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'timestamp' => now(),
        ]);
    }
}
