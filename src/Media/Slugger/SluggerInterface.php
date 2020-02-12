<?php

declare(strict_types=1);

namespace Text\Slugger;

interface SluggerInterface
{
    public function slugify(string $string, array $options = []): string;
}
