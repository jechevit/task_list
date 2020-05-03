<?php


namespace core\forms;


use core\helpers\StatusHelper;
use yii\base\Model;

class TaskForm extends Model
{
    public $title;
    public $priority;

    public function rules()
    {
        return [
            [['title', 'priority'], 'required'],
            ['title', 'string'],
            ['priority', 'in', 'range' => array_keys(StatusHelper::statusesList())],
        ];
    }
}