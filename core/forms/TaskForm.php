<?php

namespace core\forms;

use core\entities\Task;
use core\helpers\PriorityHelper;

/**
 * Class TaskForm
 * @package core\forms
 *
 * @property TagsForm $tags
 */
class TaskForm extends CompositeForm
{
    public $title;
    public $priority;

    public function __construct(Task $task = null, $config = [])
    {
        if ($task){
            $this->title = $task->title;
            $this->priority = $task->priority;
            $this->tags = new TagsForm($task);
        } else  {
            $this->tags = new TagsForm();
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['title', 'priority'], 'required'],
            ['title', 'string', 'max' => 300],
            ['priority', 'integer'],
            ['priority', 'in', 'range' => array_keys(PriorityHelper::priorityList())],
        ];
    }

    protected function internalForms(): array
    {
        return ['tags',];
    }
}