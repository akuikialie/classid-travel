<?php

namespace App\Enums;

enum RoleEnum: string
{
    case SuperAdministrator  = 'super_administrator';
    case Admin = 'administrator';
    case Jamaah = 'jamaah';

    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this){
            self::SuperAdministrator => 'Super Administrator',
            self::Admin => 'Admin',
            self::Jamaah => 'jamaah',
        };
    }

    /**
     * @return string
     */
    public function roleDetail(): string
    {
        return match ($this){
            self::SuperAdministrator => 'Super Administrator',
            self::Admin => 'Administrator is Tenant Admin',
            self::Jamaah => 'Jamaah',
        };
    }

    /**
     * @return string
     */
    public function keyValue(): string
    {
        return match ($this){
            self::SuperAdministrator => 'super_administrator',
            self::Admin => 'administrator',
            self::Jamaah => 'jamaah',
        };
    }
}
