<?php

namespace core\entities;

use Assert\Assertion;
use Assert\AssertionFailedException;

class Title
{
    /**
     * @var string
     */
    private $title;

    /**
     * Title constructor.
     * @param string $title
     * @throws AssertionFailedException
     */
    public function __construct(string $title)
    {
        Assertion::notEmpty($title);

        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}