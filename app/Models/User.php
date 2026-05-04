<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property int $role_id
 * @property int|null $desa_id
 * @property bool $is_active
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Role $role
 * @property-read Collection<int, PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read Village|null $village
 *
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDesaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
use Laravel\Sanctum\PersonalAccessToken;

class User extends Authenticatable
{
    use Auditable, HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'desa_id',
        'district_id',
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

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function activeVillageLeader(): HasOne
    {
        return $this->hasOne(VillageLeader::class)->where('is_active', true);
    }

    public function activeDistrictLeader(): HasOne
    {
        return $this->hasOne(DistrictLeader::class)->where('is_active', true);
    }

    public function hasPermission(string $permissionSlug): bool
    {
        return $this->role && $this->role->permissions()->where('slug', $permissionSlug)->exists();
    }

    public function isSuperAdmin(): bool
    {
        return $this->role && $this->role->slug === 'superadmin';
    }

    public function isOperatorDesa(): bool
    {
        return $this->role && $this->role->slug === 'operatordesa';
    }

    public function isPetugasFrontOffice(): bool
    {
        return $this->role && $this->role->slug === 'petugasfrontoffice';
    }

    public function isAuditor(): bool
    {
        return $this->role && $this->role->slug === 'auditor';
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(UserCertificate::class);
    }
}
