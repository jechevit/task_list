<?php


namespace core\helpers;


use core\entities\Priority;

class PriorityHelper
{
    public static function priorityList(): array
    {
        return [
            Priority::LOW => 'Низкий',
            Priority::MIDDLE => 'Средний',
            Priority::HIGH => 'Высокий',
        ];
    }
}