<?php

namespace App\Enums;

enum PersonId: string
{
    case KTP = 'ktp';
    case PASSPORT = 'passport';

    public function label(): string
    {
        return match($this) {
            self::KTP => 'KTP',
            self::PASSPORT => 'Paspor',
        };
    }

    public function longLabel(): string
    {
        return match($this) {
            self::KTP => 'Kartu Tanda Penduduk',
            self::PASSPORT => 'Paspor',
        };
    }
}
