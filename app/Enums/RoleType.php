<?php

namespace App\Enums;

enum RoleType: string
{
    case app  = 'app';
    case tenant = 'tenant';

    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this){
            self::app => 'For App',
            self::tenant => 'For Tenant',
        };
    }
    /**
     * @return string
     */
    public function keyValue(): string
    {
        return match ($this){
            self::app => 'app',
            self::tenant => 'tenant',
        };
    }
}
