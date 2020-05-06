<?php

namespace core\entities;

use Assert\AssertionFailedException;
use core\databases\Table;
use DomainException;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class Task
 * @package core\entities
 * @property int $id
 * @property string $uuid
 * @property string $title
 * @property int $current_status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Priority $priority
 * @property TagAssignments[] $tagAssignments
 * @property Tag[] $tags
 */
class Task extends ActiveRecord
{
    /**
     * @var Priority
     */
    private $priority;

    /**
     * @param TaskUuid $uuid
     * @param Title $title
     * @param Priority $priority
     * @return static
     * @throws AssertionFailedException
     */
    public static function create(
        TaskUuid $uuid,
        Title $title,
        Priority $priority
    ): self
    {
        $task = new static();
        $task->uuid = $uuid->getUuid();
        $task->title = $title->getTitle();
        $task->setPriority($priority->getValue());
        $task->addStatus(Status::IN_WORK);
        $task->created_at = time();
        return $task;
    }

    /**
     * @param Title $title
     * @param Priority $priority
     */
    public function edit(
        Title $title,
        Priority $priority
    ): void
    {
        $this->title = $title->getTitle();
        $this->priority = $priority;
        $this->updated_at = time();
    }

    /**
     * @throws AssertionFailedException
     */
    public function inWork(): void
    {
        if ($this->isInWork()) {
            throw new DomainException('Task already is in work!');
        }
        $this->addStatus(Status::IN_WORK);
    }

    /**
     * @return bool
     */
    public function isInWork(): bool
    {
        return $this->getCurrentStatus()->isInWork();
    }

    /**
     * @throws AssertionFailedException
     */
    public function complete(): void
    {
        if ($this->isCompleted()) {
            throw new DomainException('Task already is completed');
        }
        $this->addStatus(Status::COMPLETED);
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->getCurrentStatus()->isCompleted();
    }

    /**
     * @return Status
     * @throws AssertionFailedException
     */
    public function getCurrentStatus(): Status
    {
        return new Status($this->current_status);
    }

    /**
     * @param $value
     */
    private function addStatus($value): void
    {
        $this->current_status = $value;
    }


    /**
     * @throws AssertionFailedException
     */
    public function toLow(): void
    {
        if ($this->isLow()) {
            throw new DomainException('Task has a low priority');
        }
        $this->setPriority(Priority::LOW);
    }

    /**
     * @return bool
     */
    public function isLow(): bool
    {
        return $this->getCurrentPriority()->isLow();
    }

    /**
     * @throws AssertionFailedException
     */
    public function toMiddle(): void
    {
        if ($this->isMiddle()) {
            throw new DomainException('Task has a middle priority');
        }
        $this->setPriority(Priority::MIDDLE);
    }

    /**
     * @return bool
     */
    public function isMiddle(): bool
    {
        return $this->getCurrentPriority()->isMiddle();
    }

    /**
     * @throws AssertionFailedException
     */
    public function toHigh(): void
    {
        if ($this->isHigh()) {
            throw new DomainException('Task has a high priority');
        }
        $this->setPriority(Priority::HIGH);
    }

    /**
     * @return bool
     */
    public function isHigh(): bool
    {
        return $this->getCurrentPriority()->isHigh();
    }

    /**
     * @return Priority
     */
    public function getCurrentPriority(): Priority
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     * @throws AssertionFailedException
     */
    private function setPriority(int $priority): void
    {
        $this->priority = new Priority($priority);
    }

    public function assignTag($id): void
    {
        $assignments = $this->tagAssignments;
        foreach ($assignments as $assignment) {
            if ($assignment->isForTag($id)) {
                return;
            }
        }
        $assignments[] = TagAssignments::create($id);
        $this->tagAssignments = $assignments;
    }

    public function revokeTag($id): void
    {
        $assignments = $this->tagAssignments;
        foreach ($assignments as $i => $assignment) {
            if ($assignment->isForTag($id)) {
                unset($assignments[$i]);
                $this->tagAssignments = $assignments;
                return;
            }
        }
        throw new DomainException('Assignment is not found.');
    }

    public function revokeTags(): void
    {
        $this->tagAssignments = [];
    }

    public static function tableName()
    {
        return TABLE::TASKS;
    }

    /**
     * @return ActiveQuery
     */
    public function getTagAssignments(): ActiveQuery
    {
        return $this->hasMany(TagAssignments::class, ['task_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTags(): ActiveQuery
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->via('tagAssignments');
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['tagAssignments'],
            ],
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @throws AssertionFailedException
     */
    public function afterFind()
    {
        $this->priority = new Priority(
            $this->getAttribute('priority')
        );

        parent::afterFind();
    }

    public function beforeSave($insert)
    {
        $this->setAttribute('priority', $this->priority->getValue());

        return parent::beforeSave($insert);
    }
}