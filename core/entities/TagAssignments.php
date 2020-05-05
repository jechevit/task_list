<?php


namespace core\entities;


use core\databases\Table;
use yii\db\ActiveRecord;

/**
 * Class TagAssignments
 * @package core\entities
 *
 * @property int $tag_id
 * @property int $task_id
 */
class TagAssignments extends ActiveRecord
{
    public static function create($tagId): self
    {
        $assignment = new static();
        $assignment->tag_id = $tagId;
        return $assignment;
    }

    public function isForTag($id): bool
    {
        return $this->tag_id == $id;
    }

    public static function tableName(): string
    {
        return Table::TAGS_ASSIGNMENTS;
    }
}