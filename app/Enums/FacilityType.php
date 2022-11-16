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

    /**
     * @return string
     */
    public function icon(): string
    {
        return match ($this){
            self::perjalanan => 'bx bx-car bx-tada',
            self::penginapan => 'bx bx-building-house bx-tada',
            self::makanan => 'bx bxs-bowl-hot bx-tada',
        };
    }


}
