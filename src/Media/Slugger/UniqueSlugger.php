<?php

declare(strict_types=1);

namespace Media\Slugger;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UniqueSlugger implements SluggerInterface
{
    private EntityManagerInterface $em;
    private OptionsResolver $resolver;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->configure($this->resolver = new OptionsResolver());
    }

    public function slugify(string $string, array $options = []): string
    {
        $options = $this->resolver->resolve($options);

        do {
            $hash = $this->generate($options['length']);
        } while ($this->isNotUnique($hash, $options));

        return $hash;
    }

    private function generate(int $length): string
    {
        $string = '';
        do {
            $hex = \uniqid();
            $base64 = \base64_encode(pack('H*', $hex));
            $base64 = \strtr($base64, [
                '/' => '_',
                '+' => '-',
                '=' => '',
            ]);

            $string .= $base64;
        } while (\strlen($string) < $length);

        return \substr($base64, 0, $length);
    }

    private function configure(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'length' => 10,
            'class' => null,
            'field' => null,
        ]);

        /** @var EntityManager $em */
        $em = $this->em;
        $resolver->setRequired(['class', 'field']);
        $resolver->setAllowedTypes('length', 'int');
        $resolver->setAllowedTypes('class', 'string');
        $resolver->setAllowedValues('class', function (string $value) use ($em): bool {
            return \class_exists($value) && !$em->getMetadataFactory()->isTransient($value);
        });
        $resolver->setAllowedTypes('field', 'string');
        $resolver->setNormalizer('field', function (Options $options, string $value) use ($em): string {
            $class = $em->getClassMetadata($options['class']);
            if (!$class->hasField($value)) {
                throw new InvalidOptionsException(\sprintf('Field %s is not mapped to %s.', $value, $options['class']));
            }

            return $value;
        });
    }

    private function isNotUnique(string $hash, array $options): bool
    {
        $er = $this->em->getRepository($options['class']);

        return (bool) $er->findOneBy([$options['field'] => $hash]);
    }
}
