<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class Queue extends Model
{
    use HasFactory;

    protected $table = 'mpp_queues';

    public const STATUS_WAITING_FO = 'waiting_fo';

    public const STATUS_CALLING_FO = 'calling_fo';

    public const STATUS_WAITING_GERAI = 'waiting_gerai';

    public const STATUS_CALLING_GERAI = 'calling_gerai';

    public const STATUS_DONE = 'done';

    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'number',
        'service_id',
        'counter_id',
        'counter_name',
        'fo_petugas_id',
        'gerai_petugas_id',
        'status',
        'called_at',
        'fo_called_at',
        'fo_finished_at',
        'gerai_called_at',
        'done_at',
        'notes',
        'alasan_reject',
        'skipped_at',
        'is_priority',
        'priority_type',
    ];

    /**
     * Daftar kategori antrian prioritas yang valid (Lansia, Ibu Hamil, dll).
     */
    public const PRIORITY_TYPES = [
        'lansia' => 'Lansia',
        'ibu_hamil' => 'Ibu Hamil',
        'difabel' => 'Difabel',
        'ibu_balita' => 'Ibu Balita',
    ];

    /**
     * Label kategori prioritas (null bila bukan tiket prioritas).
     */
    public function getPriorityLabelAttribute(): ?string
    {
        if (! $this->is_priority) {
            return null;
        }

        return self::PRIORITY_TYPES[$this->priority_type] ?? 'Prioritas';
    }

    protected static function booted(): void
    {
        static::updated(function (Queue $queue) {
            if ($queue->wasChanged('status')) {
                $queue->syncLinkedRequestStatus();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'called_at' => 'datetime',
            'fo_called_at' => 'datetime',
            'fo_finished_at' => 'datetime',
            'gerai_called_at' => 'datetime',
            'done_at' => 'datetime',
            'skipped_at' => 'datetime',
            'is_priority' => 'boolean',
        ];
    }

    public function counter(): BelongsTo
    {
        return $this->belongsTo(Counter::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(MppService::class, 'service_id');
    }

    public function serviceRequest(): HasOne
    {
        return $this->hasOne(MppServiceRequest::class, 'queue_id');
    }

    /**
     * Selaraskan status pengajuan yang ter-link dengan status tiket ini.
     * Dipanggil otomatis lewat event `updated` setiap kali status berubah,
     * sehingga mpp_service_requests tidak pernah tertinggal dari mpp_queues.
     */
    public function syncLinkedRequestStatus(): void
    {
        $request = $this->serviceRequest()->first();

        if (! $request) {
            return;
        }

        $request->status = MppServiceRequest::mapQueueStatus($this->status);
        $request->save();
    }

    public function getDurasiFoAttribute(): ?string
    {
        return self::formatDurasi($this->fo_called_at, $this->fo_finished_at);
    }

    public function getDurasiFoDetikAttribute(): ?int
    {
        return self::durasiDetik($this->fo_called_at, $this->fo_finished_at);
    }

    public function getDurasiGeraiAttribute(): ?string
    {
        return self::formatDurasi($this->gerai_called_at, $this->done_at);
    }

    public function getDurasiGeraiDetikAttribute(): ?int
    {
        return self::durasiDetik($this->gerai_called_at, $this->done_at);
    }

    public function getDurasiTotalAttribute(): ?string
    {
        return self::formatDurasi($this->created_at, $this->done_at);
    }

    public function getDurasiTotalDetikAttribute(): ?int
    {
        return self::durasiDetik($this->created_at, $this->done_at);
    }

    /**
     * Hitung durasi dalam detik antara dua timestamp. Mengembalikan null
     * bila salah satu atau kedua timestamp belum ada.
     */
    public static function durasiDetik(?Carbon $from, ?Carbon $to): ?int
    {
        if (! $from || ! $to) {
            return null;
        }

        return max(0, (int) $from->diffInSeconds($to));
    }

    /**
     * Format durasi menjadi "X menit Y detik", atau null bila belum lengkap.
     */
    public static function formatDurasi(?Carbon $from, ?Carbon $to): ?string
    {
        $seconds = self::durasiDetik($from, $to);

        if ($seconds === null) {
            return null;
        }

        $minutes = intdiv($seconds, 60);
        $remaining = $seconds % 60;

        return "{$minutes} menit {$remaining} detik";
    }

    public function foPetugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'fo_petugas_id');
    }

    public function geraiPetugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gerai_petugas_id');
    }

    public function scopeWaitingFo(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_WAITING_FO);
    }

    public function scopeCallingFo(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_CALLING_FO);
    }

    public function scopeWaitingGerai(Builder $query, ?int $opdId): Builder
    {
        return $query->where('status', self::STATUS_WAITING_GERAI)
            ->whereHas('service', function (Builder $q) use ($opdId) {
                $q->where('opd_id', $opdId);
            });
    }

    public function scopeCallingGerai(Builder $query, int $counterId): Builder
    {
        return $query->where('status', self::STATUS_CALLING_GERAI)
            ->where('counter_id', $counterId);
    }

    public function scopeTodayForService(Builder $query, int $serviceId): Builder
    {
        return $query->where('service_id', $serviceId)
            ->whereDate('created_at', today());
    }

    /**
     * Urutkan antrian prioritas lebih dulu, lalu FIFO berdasarkan created_at.
     */
    public function scopePriorityFirst(Builder $query): Builder
    {
        return $query->orderByDesc('is_priority')
            ->orderBy('created_at');
    }
}
