<?php

namespace App\Enums;

enum VirtualAccount: string
{
    case Tabungan = 'tabungan';
    case Perencanaan = 'perencanaan';

    public function label(): string
    {
        return match($this){
            self::Tabungan => 'Tabungan Pribadi',
            self::Perencanaan => 'Paket Tabungan',
        };
    }

    public function keyValue(): string
    {
        return match($this){
            self::Tabungan => 'tabungan',
            self::Perencanaan => 'perencanaan',
        };
    }
}
