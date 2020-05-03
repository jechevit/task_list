<?php


namespace core\helpers;


use core\entities\Status;

class StatusHelper
{
    public static function statusesList(): array
    {
        return [
            Status::DRAFT => 0,
            Status::IN_WORK => 1,
            Status::COMPLETED => 2,
        ];
    }
}