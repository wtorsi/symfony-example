<?php

declare(strict_types=1);

namespace Security\Exception;

class InvalidCsrfTokenException extends \Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException implements
    ExceptionInterface
{
}
