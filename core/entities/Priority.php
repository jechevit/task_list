<?php

namespace core\entities;

use Assert\Assertion;
use Assert\AssertionFailedException;

class Priority
{
    const LOW = 0;
    const MIDDLE = 1;
    const HIGH = 2;

    /**
     * @var int
     */
    private $value;

    /**
     * Priority constructor.
     * @param int $value
     * @throws AssertionFailedException
     */
    public function __construct(int $value)
    {
        Assertion::inArray($value, [
            self::LOW,
            self::MIDDLE,
            self::HIGH
        ]);

        $this->value = $value;
    }

    /**
     * @return bool
     */
    public function isLow(): bool
    {
        return $this->value === self::LOW;
    }

    /**
     * @return bool
     */
    public function isMiddle(): bool
    {
        return $this->value === self::MIDDLE;
    }

    /**
     * @return bool
     */
    public function isHigh(): bool
    {
        return $this->value === self::HIGH;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }
}