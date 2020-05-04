<?php

namespace core\forms;

use core\helpers\PriorityHelper;
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
            ['priority', 'in', 'range' => array_keys(PriorityHelper::priorityList())],
        ];
    }
}