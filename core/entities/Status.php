<?php

namespace core\entities;

use Assert\Assertion;
use Assert\AssertionFailedException;

class Status
{
    const IN_WORK = 1;
    const COMPLETED = 2;

    /**
     * @var mixed
     */
    private $value;

    /**
     * Status constructor.
     * @param $value
     * @throws AssertionFailedException
     */
    public function __construct($value)
    {
        Assertion::inArray($value, [
            self::IN_WORK,
            self::COMPLETED
        ]);

        $this->value = $value;
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
}