<?php

namespace App\Concerns\RBAC;

use ArchTech\Enums\Values;
use Illuminate\Support\Arr;

trait PermissionEnumHelper
{
    use Values;
    // when create new permission mandatory format is INDEX, CREATE, SHOW, UPDATE, DELETE

    /**
     * @return bool
     */
    public function isPermissionRead(): bool
    {
        $separateValue = explode(separator: '_', string: $this->value);
        return match ($separateValue[1]){
            'index', 'show' => true,
            default => false
        };
    }

    /**
     * @return string
     */
    public function mapReadPermission(): string
    {
        $separateValue = explode(separator: '_', string: $this->value);
        return match ($separateValue[1]){
            'index' => 'Page',
            'show' => 'Detail',
        };
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        $separateValue = explode(separator: '_', string: $this->value);
        if ($this->isPermissionRead()){
            $mapReadPermission = $this->mapReadPermission();
            $label = "{$separateValue[0]} | View {$mapReadPermission}";
        }else{
            $combineSeparateValue = implode(separator: ' ', array: Arr::except($separateValue, [0]));
            $label = "{$separateValue[0]} | can {$combineSeparateValue}";
        }

        return match ($this){
            default => ucwords($label),
        };
    }

    /**
     * @return string
     */
    public function getPermissionName(): string
    {
        return $this->value;
    }
}
