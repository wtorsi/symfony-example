<?php declare(strict_types=1);

namespace Location\Detector;

use Location\Entity\City;
use Symfony\Component\HttpFoundation\Request;

interface CityDetectorInterface
{
    public function detect(Request $request): ?City;
}