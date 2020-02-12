<?php declare(strict_types=1);

namespace Security\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class FormAuthenticationException extends AuthenticationException implements ExceptionInterface
{
    /**
     * @var array
     */
    private $errors = [];

    public function __construct(array $errors, string $message = 'Authentication failed by form errors.', int $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * {@inheritdoc}
     */
    public function __serialize(): array
    {
        return [parent::__serialize(), $this->errors];
    }

    /**
     * {@inheritdoc}
     */
    public function __unserialize(array $data): void
    {
        [$parentData, $this->errors] = $data;
        parent::__unserialize($parentData);
    }
}