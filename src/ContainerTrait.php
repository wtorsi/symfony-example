<?php

declare(strict_types=1);

use Dev\PaginationBundle\PaginationTrait;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * @mixin ContainerAwareTrait
 */
trait ContainerTrait
{
    use FormTrait;
    use ControllerTrait;
    use PaginationTrait;
}
