<?php

declare(strict_types=1);

namespace Form\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
class Url extends Constraint
{
    public string $invalidUrlMessage = 'url.illegal_chars';

    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
