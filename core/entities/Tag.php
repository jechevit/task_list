<?php

namespace core\entities;

use core\databases\Table;
use yii\db\ActiveRecord;

/**
 * Class Tag
 * @package core\entities
 *
 * @property int $id
 * @property string $name
 */
class Tag extends ActiveRecord
{
    public static function create($name): self
    {
        $tag = new static();
        $tag->name = $name;
        return $tag;
    }

    public function edit($name): void
    {
        $this->name = $name;
    }

    public static function tableName(): string
    {
        return Table::TAGS;
    }
}