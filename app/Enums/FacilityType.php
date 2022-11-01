<?php

namespace App\Enums;

enum FacilityType: string
{
    case perjalanan = 'perjalanan';
    case penginapan = 'penginapan';
    case makanan = 'makanan';

    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this){
            self::perjalanan => 'Fasilitas Perjalanan',
            self::penginapan => 'Fasilitas Penginapan',
            self::makanan => 'Fasilitas Makanan',
        };
    }

    /**
     * @return string
     */
    public function keyValue(): string
    {
        return match ($this){
            self::perjalanan => 'perjalanan',
            self::penginapan => 'penginapan',
            self::makanan => 'makanan',
        };
    }


}
