<?php

namespace core\entities;

use Assert\AssertionFailedException;
use core\databases\Table;
use DateTimeImmutable;
use DomainException;
use yii\db\ActiveRecord;
use yii\helpers\Json;

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
 * @property Tag[] $tags
 * @property Status[] $statuses
 */
class Task extends ActiveRecord
{
    public $statuses = [];

    /**
     * @param TaskUuid $uuid
     * @param $title
     * @param Priority $priority
     * @return static
     * @throws AssertionFailedException
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
        $task->addStatus(Status::DRAFT);
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

    /**
     * @throws AssertionFailedException
     */
    public function draft(): void
    {
        if ($this->isDraft()) {
            throw new DomainException('Task already is draft');
        }
        $this->addStatus(Status::DRAFT);
    }

    /**
     * @return bool
     */
    public function isDraft(): bool
    {
        return $this->getCurrentStatus()->isDraft();
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
     */
    private function getCurrentStatus(): Status
    {
        return end($this->statuses);
    }

    /**
     * @param $value
     * @throws AssertionFailedException
     */
    private function addStatus($value): void
    {
        $this->statuses[] = new Status($value, new DateTimeImmutable());
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
    private function getCurrentPriority(): Priority
    {
        return $this->priority;
    }

    /**
     * @param $priority
     * @throws AssertionFailedException
     */
    private function setPriority($priority): void
    {
        $this->priority = new Priority($priority);
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

    public function afterFind(): void
    {
        $this->statuses = array_map(function ($row) {
            return new Status(
                $row['value'],
                new DateTimeImmutable($row['date'])
            );
        }, Json::decode($this->getAttribute('statuses')));

        parent::afterFind();
    }

    public function beforeSave($insert): bool
    {
        $this->setAttribute('statuses', Json::encode(array_map(function (Status $status) {
            return [
                'value' => $status->getValue(),
                'date' => $status->getDate()->format(DATE_RFC3339),
            ];
        }, $this->statuses)));


        return parent::beforeSave($insert);
    }
}