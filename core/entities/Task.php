<?php

namespace core\entities;

use core\databases\Table;
use DomainException;
use yii\db\ActiveRecord;

/**
 * Class Task
 * @package core\entities
 * @property int $id
 * @property string $uuid
 * @property string $title
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Priority $priority
 * @property Status $current_status
 * @property Tag[] $tags
 */
class Task extends ActiveRecord
{
    /**
     * @param TaskUuid $uuid
     * @param $title
     * @param Priority $priority
     * @return static
     */
    public static function create(
        TaskUuid $uuid,
        $title,
        Priority $priority
    ): self
    {
        $task = new static();
        $task->uuid = $uuid->getUuid();
        $task->title = $title;
        $task->setPriority($priority);
        $task->setCurrentStatus(new Status(Status::IN_WORK));
        $task->created_at = time();
        return $task;
    }

    /**
     * @param $title
     * @param Priority $priority
     */
    public function edit(
        $title,
        Priority $priority
    ): void
    {
        $this->title = $title;
        $this->priority = $priority;
        $this->updated_at = time();
    }

    public function inWork(): void
    {
        if ($this->isInWork()) {
            throw new DomainException('Task already is in work!');
        }
        $this->setCurrentStatus(new Status(Status::IN_WORK));
    }

    /**
     * @return bool
     */
    public function isInWork(): bool
    {
        return $this->current_status->isInWork();
    }

    public function complete(): void
    {
        if ($this->isCompleted()) {
            throw new DomainException('Task already is completed');
        }
        $this->setCurrentStatus(new Status(Status::COMPLETED));
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->current_status->isCompleted();
    }

    /**
     * @param Status $value
     */
    private function setCurrentStatus(Status $value): void
    {
        $this->current_status = $value->getValue();
    }


    public function toLow(): void
    {
        if ($this->isLow()) {
            throw new DomainException('Task has a low priority');
        }
        $this->setPriority(new Priority(Priority::LOW));
    }

    /**
     * @return bool
     */
    public function isLow(): bool
    {
        return $this->getCurrentPriority()->isLow();
    }

    public function toMiddle(): void
    {
        if ($this->isMiddle()) {
            throw new DomainException('Task has a middle priority');
        }
        $this->setPriority(new Priority(Priority::MIDDLE));
    }

    /**
     * @return bool
     */
    public function isMiddle(): bool
    {
        return $this->getCurrentPriority()->isMiddle();
    }

    public function toHigh(): void
    {
        if ($this->isHigh()) {
            throw new DomainException('Task has a high priority');
        }
        $this->setPriority(new Priority(Priority::HIGH));
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
    private function getCurrentPriority(): Priority
    {
        return $this->priority;
    }

    /**
     * @param Priority $priority
     */
    private function setPriority(Priority $priority): void
    {
        $this->priority = $priority->getValue();
    }

    public static function tableName()
    {
        return TABLE::TASKS;
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
}