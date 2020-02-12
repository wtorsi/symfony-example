<?php

declare(strict_types=1);

namespace Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SubmittedFormRequiredException extends BadRequestHttpException implements ExceptionInterface
{
    public function __construct(string $type, \Exception $previous = null)
    {
        parent::__construct(sprintf('Submitted form of type %s required', $type), $previous);
    }
}
