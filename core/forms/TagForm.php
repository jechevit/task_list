<?php

namespace core\forms;

use yii\base\Model;

class TagForm extends Model
{
    public $name;

    public function rules(): array
    {
        return [
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 10],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Название тега'
        ];
    }
}