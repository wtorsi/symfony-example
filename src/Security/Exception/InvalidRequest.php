<?php

declare(strict_types=1);

namespace Security\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class InvalidRequest extends AuthenticationException implements ExceptionInterface
{
}
