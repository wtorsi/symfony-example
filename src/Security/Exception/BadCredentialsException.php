<?php

declare(strict_types=1);

namespace Security\Exception;

class BadCredentialsException extends \Symfony\Component\Security\Core\Exception\BadCredentialsException implements
    ExceptionInterface
{
}
