<?php

namespace core\forms;

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

    public function __construct($config = [])
    {
        $this->tags = new TagsForm();
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['title', 'priority'], 'required'],
            ['title', 'string'],
            ['priority', 'in', 'range' => array_keys(PriorityHelper::priorityList())],
        ];
    }

    protected function internalForms(): array
    {
        return ['tags',];
    }
}