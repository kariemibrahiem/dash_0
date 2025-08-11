<?php

namespace App\Enums;



enum PermissionEnums: string
{

// i neeed to but it here one by one ok ?

    public function label(): string
    {
        return trns($this->value);
    }


     public function permissions() :array {
        return [
            $this->value."_create",
            $this->value."_update",
            $this->value."_read",
            $this->value."_delete",
        ];
    }
}