<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $mpp_service_id
 * @property int|null $front_office_user_id
 * @property array|null $submitted_form_data
 * @property string $status
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read MppService $mppService
 * @property-read User|null $frontOfficeUser
 *
 * @mixin \Eloquent
 */
class MppServiceRequest extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'PENDING';

    public const STATUS_PROCESSED = 'PROCESSED';

    public const STATUS_COMPLETED = 'COMPLETED';

    public const STATUS_REJECTED = 'REJECTED';

    /**
     * Petakan status tiket antrian (mpp_queues.status) ke status pengajuan
     * (mpp_service_requests.status) agar kedua tabel selalu selaras.
     */
    public static function mapQueueStatus(string $queueStatus): string
    {
        return match ($queueStatus) {
            Queue::STATUS_CALLING_FO,
            Queue::STATUS_WAITING_GERAI,
            Queue::STATUS_CALLING_GERAI => self::STATUS_PROCESSED,
            Queue::STATUS_DONE => self::STATUS_COMPLETED,
            Queue::STATUS_REJECTED => self::STATUS_REJECTED,
            default => self::STATUS_PENDING,
        };
    }

    protected $fillable = [
        'mpp_service_id',
        'nomor_antrian',
        'queue_id',
        'front_office_user_id',
        'submitted_form_data',
        'status',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'submitted_form_data' => 'array',
        ];
    }

    /**
     * Get the applicant name from submitted form data.
     * Searches for a field labelled 'nama' or similar.
     */
    public function getApplicantNameAttribute(): string
    {
        if ($this->submitted_form_data && is_array($this->submitted_form_data)) {
            foreach ($this->submitted_form_data as $field) {
                if (isset($field['label']) && in_array(strtolower($field['label']), ['nama', 'nama lengkap', 'nama pemohon', 'name'])) {
                    if (! empty($field['value'])) {
                        return (string) $field['value'];
                    }
                }
            }
        }

        return $this->mppService?->name ?? 'Pemohon Dinamis';
    }

    public function mppService(): BelongsTo
    {
        return $this->belongsTo(MppService::class, 'mpp_service_id');
    }

    public function queue(): BelongsTo
    {
        return $this->belongsTo(Queue::class, 'queue_id');
    }

    public function frontOfficeUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'front_office_user_id');
    }
}
