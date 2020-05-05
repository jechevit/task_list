<?php


namespace core\helpers;


use core\entities\Status;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class StatusHelper
{
    public static function statusesList(): array
    {
        return [
            Status::IN_WORK => 'В работе',
            Status::COMPLETED => 'Завершена',
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusesList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case Status::COMPLETED:
                $class = 'label label-success';
                break;

            default:
                $class = 'label label-primary';
        }

        return Html::tag('span', ArrayHelper::getValue(self::statusesList(), $status), [
            'class' => $class,
        ]);
    }
}