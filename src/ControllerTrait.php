<?php

declare(strict_types=1);

use Exception\NotFoundHttpException;
use Exception\SubmittedFormRequiredException;
use Exception\UnderConstructionException;

trait ControllerTrait
{
    /**
     * Returns a NotFoundHttpException.
     *
     * This will result in a 404 response code. Usage example:
     *
     *     throw $this->createNotFoundException('Page not found!');
     *
     * @final
     *
     * @param string         $message
     * @param Exception|null $previous
     *
     * @return NotFoundHttpException
     */
    protected function createNotXmlHttpRequestException(string $message = 'Not XML HTTP request passed', \Exception $previous = null): NotFoundHttpException
    {
        return new NotFoundHttpException($message, $previous);
    }

    protected function createSubmittedFormRequiredException(string $type): SubmittedFormRequiredException
    {
        return new SubmittedFormRequiredException($type);
    }

    protected function createUnderConstructionException(): UnderConstructionException
    {
        return new UnderConstructionException();
    }
}
