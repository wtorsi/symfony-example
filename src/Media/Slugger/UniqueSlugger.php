<?php

declare(strict_types=1);

namespace Media\Slugger;

use Doctrine\ORM\EntityManagerInterface;
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
        $hex = \uniqid();
        $base64 = \base64_encode(pack('H*', $hex));
        $base64 = \strtr($base64, [
            '/' => '_',
            '+' => '-',
            '=' => '',
        ]);

        return \substr($base64, 0, $length);
    }

    private function configure(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'length' => 8,
            'class' => '',
            'field' => '',
        ]);

        $resolver->setRequired(['class', 'field']);
        $resolver->setAllowedTypes('length', 'int');
        $resolver->setAllowedTypes('class', 'string');
        $resolver->setAllowedTypes('field', 'string');
    }

    private function isNotUnique(string $hash, array $options): bool
    {
        $er = $this->em->getRepository($options['class']);

        return (bool) $er->findOneBy([$options['field'] => $hash]);
    }
}
