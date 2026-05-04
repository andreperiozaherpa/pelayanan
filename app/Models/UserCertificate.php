<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCertificate extends Model
{
    protected $fillable = [
        'user_id',
        'certificate_path',
        'passphrase',
        'valid_until',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'valid_until' => 'date',
            'passphrase' => 'encrypted',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
