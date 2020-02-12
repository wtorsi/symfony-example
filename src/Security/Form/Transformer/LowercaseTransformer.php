<?php declare(strict_types=1);

namespace Security\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class LowercaseTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if (null === $value || '' === $value) {
            return $value;
        }

        return \mb_strtolower($value);
    }

    public function reverseTransform($value)
    {
        return $this->transform($value);
    }
}