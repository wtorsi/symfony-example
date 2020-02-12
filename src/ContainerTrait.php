<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Trait ContainerTrait.
 *
 * @mixin ContainerAwareTrait
 */
trait ContainerTrait
{
    use FormTrait;
    use ControllerTrait;
}
