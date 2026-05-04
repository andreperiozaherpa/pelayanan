<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $district_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Citizen> $citizens
 * @property-read int|null $citizens_count
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Village newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Village newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Village query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Village whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Village whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Village whereDistrictName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Village whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Village whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Village whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Village extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'district_id',
    ];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function citizens(): HasMany
    {
        return $this->hasMany(Citizen::class, 'desa_id', 'id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'desa_id', 'id');
    }

    public function leaders(): HasMany
    {
        return $this->hasMany(VillageLeader::class);
    }

    public function activeLeader(): HasOne
    {
        return $this->hasOne(VillageLeader::class)->where('is_active', true);
    }
}
