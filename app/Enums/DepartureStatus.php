<?php

namespace App\Enums;

enum DepartureStatus: string
{
    case SUDAH_BERANGKAT = 'sudah_berangkat';
    case SEDANG_BERANGKAT = 'sedang_berangkat';
    case BELUM_BERANGKAT = 'belum_berangkat';
    case BATAL_BERANGKAT = 'batal_berangkat';

    public function label(): string
    {
        return match($this) {
            self::SUDAH_BERANGKAT => 'Sudah Berangkat',
            self::SEDANG_BERANGKAT => 'Sedang Berangkat',
            self::BELUM_BERANGKAT => 'Belum Berangkat',
            self::BATAL_BERANGKAT => 'Batal Berangkat',
        };
    }

    public function labelDescription(): string
    {
        return match($this) {
            self::SUDAH_BERANGKAT => 'Jamaah Sudah Berangkat',
            self::SEDANG_BERANGKAT => 'Jamaah Sedang Berangkat',
            self::BELUM_BERANGKAT => 'Jamaah Belum Berangkat',
            self::BATAL_BERANGKAT => 'Jamaah Batal Berangkat',
        };
    }

    public function keyValue(): string
    {
        return match($this) {
            self::SUDAH_BERANGKAT => 'SUDAH_BERANGKAT',
            self::SEDANG_BERANGKAT => 'SEDANG_BERANGKAT',
            self::BELUM_BERANGKAT => 'BELUM_BERANGKAT',
            self::BATAL_BERANGKAT => 'BATAL_BERANGKAT',
        };
    }

}
