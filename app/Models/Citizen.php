<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Citizen extends Model
{
    use HasFactory;

    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nik',
        'nama_lengkap',
        'tgl_lahir',
        'alamat_desa',
        'kontak',
        'desa_id',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'alamat_desa' => 'encrypted', // Blueprint requirement: Encrypted at application level
    ];

    public function povertyRecords(): HasMany
    {
        return $this->hasMany(PovertyRecord::class, 'citizen_nik', 'nik');
    }

    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'citizen_nik', 'nik');
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'desa_id', 'id');
    }

    public function verificationLogs(): HasMany
    {
        return $this->hasMany(VerificationLog::class, 'nik', 'nik');
    }
}
