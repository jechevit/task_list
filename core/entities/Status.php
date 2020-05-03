<?php

namespace core\entities;

use Assert\Assertion;
use Assert\AssertionFailedException;
use DateTimeImmutable;

class Status
{
    const DRAFT = 0;
    const IN_WORK = 1;
    const COMPLETED = 2;

    /**
     * @var mixed
     */
    private $value;
    /**
     * @var DateTimeImmutable
     */
    private $date;

    /**
     * Status constructor.
     * @param $value
     * @param DateTimeImmutable $date
     * @throws AssertionFailedException
     */
    public function __construct($value, DateTimeImmutable $date)
    {
        Assertion::inArray($value, [
            self::DRAFT,
            self::IN_WORK,
            self::COMPLETED
        ]);

        $this->value = $value;
        $this->date = $date;
    }

    /**
     * @return bool
     */
    public function isDraft(): bool
    {
        return $this->value === self::DRAFT;
    }

    /**
     * @return bool
     */
    public function isInWork(): bool
    {
        return $this->value === self::IN_WORK;
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->value === self::COMPLETED;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }
}