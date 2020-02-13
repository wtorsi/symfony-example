<?php

declare(strict_types=1);

namespace Dev\PaginationBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PaginationExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('render_pagination', [PaginationRuntime::class, 'render'], ['is_safe' => ['html'], 'needs_environment' => true]),
        ];
    }
}
