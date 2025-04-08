<?php

namespace App\Contracts\RBAC;

interface InteractsWithPermission
{
    // when create new permission mandatory format is INDEX, CREATE, SHOW, UPDATE, DELETE

    public static function getGroupName(): string;
    public function getPermissionName(): string;
    public function mapReadPermission(): string;
    public function isPermissionRead(): bool;
    public function usesOn(): string;
    public function getLabel(): string;
}
