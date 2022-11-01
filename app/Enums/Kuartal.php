<?php

namespace App\Enums;

enum Kuartal: string
{
    case Q1 = 'Q1';
    case Q2 = 'Q2';
    case Q3 = 'Q3';
    case Q4 = 'Q4';

    public function label(): string
    {
        return match($this) {
            self::Q1 => 'Kuartal ke-1',
            self::Q2 => 'Kuartal ke-2',
            self::Q3 => 'Kuartal ke-3',
            self::Q4 => 'Kuartal ke-4',
        };
    }

    public function keyValue(): string
    {
        return match($this) {
            self::Q1 => 'Q1',
            self::Q2 => 'Q2',
            self::Q3 => 'Q3',
            self::Q4 => 'Q4',
        };
    }

}
