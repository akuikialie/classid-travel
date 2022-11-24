<?php

namespace App\Enums;

enum Statuses: string
{
    case Active = 'active';
    case NonActive = 'nonactive';

    public function label(): string
    {
        return match($this){
            self::Active => 'Aktif',
            self::NonActive => 'Tidak Aktif',
        };
    }

    public function keyValue(): string
    {
        return match($this) {
            self::Active => 'active',
            self::NonActive => 'nonactive',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Active => 'success',
            self::NonActive => 'danger',
        };
    }
}
