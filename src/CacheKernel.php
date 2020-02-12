<?php declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheKernel extends HttpCache
{
    protected function getOptions()
    {
        return [
            'default_ttl' => 30,
        ];
    }
}