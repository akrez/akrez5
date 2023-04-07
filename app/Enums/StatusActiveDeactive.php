<?php

namespace App\Enums;

use App\Support\Enum;

class StatusActiveDeactive extends Enum
{
    public const DEACTIVE = Status::DEACTIVE;
    public const ACTIVE = Status::ACTIVE;

    public static function getItems()
    {
        return Status::getStatusItems([
            Status::DEACTIVE,
            Status::ACTIVE,
        ]);
    }
}
