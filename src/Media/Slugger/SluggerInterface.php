<?php

declare(strict_types=1);

namespace Media\Slugger;

interface SluggerInterface
{
    public function slugify(string $string, array $options = []): string;
}
