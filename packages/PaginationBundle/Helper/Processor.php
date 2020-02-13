<?php

declare(strict_types=1);

namespace Dev\PaginationBundle\Helper;

use Dev\PaginationBundle\Pagination\View\PaginationView;
use Twig\Environment;

class Processor
{
    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function render(Environment $env, PaginationView $view, string $template = null, array $viewOptions = []): string
    {
        return $env->render($template, \array_replace_recursive($view->vars, $viewOptions));
    }
}
