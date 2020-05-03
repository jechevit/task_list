<?php


namespace core\entities;


use Assert\Assertion;
use Assert\AssertionFailedException;

abstract class Uuid
{
    protected $uuid;

    /**
     * Uuid constructor.
     * @param string $uuid
     * @throws AssertionFailedException
     */
    public function __construct(string $uuid)
    {
        Assertion::notEmpty($uuid);

        $this->uuid = $uuid;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function isEqualTo(self $other): bool
    {
        return $this->getUuid() === $other->getUuid();
    }
}