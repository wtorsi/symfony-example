<?php

declare(strict_types=1);

namespace Security\Exception;

use Configuration\Exception\Exception;

class InvalidTypeException extends \InvalidArgumentException implements Exception
{
    public function __construct($value, string $expectedType)
    {
        parent::__construct(sprintf('Expected argument of type "%s", "%s" given', $expectedType, \is_object($value) ? \get_class($value) : \gettype($value)));
    }
}
