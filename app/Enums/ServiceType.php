<?php

namespace App\Enums;

enum ServiceType: string
{
    case POVERTY = 'KETERANGAN KEMISKINAN';
    case MOVE = 'PENGANTAR PINDAH';
    case ARRIVAL = 'LAPOR DATANG';
    case DOMICILE = 'KETERANGAN DOMISILI';
    case DEATH = 'SURAT KEMATIAN';

    /**
     * Get all values as an array.
     *
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
