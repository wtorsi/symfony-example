<?php declare(strict_types=1);

namespace Dev\PaginationBundle\Twig;

use Dev\PaginationBundle\Helper\Processor;
use Dev\PaginationBundle\Pagination\View\PaginationView;
use Twig\Environment;
use Twig\Extension\RuntimeExtensionInterface;

class PaginationRuntime implements RuntimeExtensionInterface
{
    /**
     * @var Processor
     */
    protected $processor;

    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * @param Environment    $env
     * @param PaginationView $pagination
     * @param string|null    $template
     * @param array          $viewParams
     *
     * @return string
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function render(Environment $env, PaginationView $pagination, string $template = null, array $viewParams = [])
    {
        return $this->processor->render($env, $pagination, $template, $viewParams);
    }
}