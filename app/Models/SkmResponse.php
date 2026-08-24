<?php

namespace App\Models;

use Database\Factories\SkmResponseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkmResponse extends Model
{
    /** @use HasFactory<SkmResponseFactory> */
    use HasFactory;

    public const UNSUR_KEYS = ['u1', 'u2', 'u3', 'u4', 'u5', 'u6', 'u7', 'u8', 'u9'];

    public const SOURCE_WEB = 'web';

    public const SOURCE_API = 'api';

    protected $table = 'skm_responses';

    protected $fillable = [
        'opd_id',
        'nama',
        'jenis_kelamin',
        'umur',
        'pendidikan',
        'pekerjaan',
        'u1',
        'u2',
        'u3',
        'u4',
        'u5',
        'u6',
        'u7',
        'u8',
        'u9',
        'saran',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'umur' => 'integer',
            'u1' => 'integer',
            'u2' => 'integer',
            'u3' => 'integer',
            'u4' => 'integer',
            'u5' => 'integer',
            'u6' => 'integer',
            'u7' => 'integer',
            'u8' => 'integer',
            'u9' => 'integer',
        ];
    }

    public function opd(): BelongsTo
    {
        return $this->belongsTo(Opd::class);
    }
}
