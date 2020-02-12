<?php

declare(strict_types=1);

namespace Security\Exception;

class CustomUserMessageAuthenticationException extends \Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException implements ExceptionInterface
{
}
