<?php declare(strict_types=1);

namespace Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UnderConstructionException extends HttpException
{
    /**
     * @param string     $message  The internal exception message
     * @param \Throwable $previous The previous exception
     * @param int        $code     The internal exception code
     */
    public function __construct(string $message = null, \Throwable $previous = null, int $code = 0, array $headers = [])
    {
        parent::__construct(202, $message, $previous, $headers, $code);
    }
}
