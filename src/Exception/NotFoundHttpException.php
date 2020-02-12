<?php

declare(strict_types=1);

namespace Exception;

class NotFoundHttpException extends \Symfony\Component\HttpKernel\Exception\NotFoundHttpException implements
    ExceptionInterface
{
}
