<?php


namespace core\helpers;


use core\entities\Priority;
use core\entities\Task;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

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

    public static function priorityName($priority): string
    {
        return ArrayHelper::getValue(self::priorityList(), $priority);
    }

    public static function priorityLabel(Task $task): string
    {
        switch ($task) {
            case $task->isMiddle():
                $class = 'label label-warning';
                break;
            case $task->isHigh():
                $class = 'label label-danger';
                break;
            default:
                $class = 'label label-info';
        }

        return Html::tag('span', ArrayHelper::getValue(self::priorityList(), $task->getCurrentPriority()->getValue()), [
            'class' => $class,
        ]);
    }
}