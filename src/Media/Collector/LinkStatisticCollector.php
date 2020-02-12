<?php declare(strict_types=1);

namespace Media\Collector;

use Location\Detector\CityDetector;
use Location\Detector\CityDetectorInterface;
use Media\Entity\Link;
use Media\Messenger\Message\OpenLinkMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

class LinkStatisticCollector
{
    private MessageBusInterface $bus;
    private CityDetectorInterface $cityDetector;

    public function __construct(MessageBusInterface $bus, CityDetectorInterface $cityDetector)
    {
        $this->bus = $bus;
        $this->cityDetector = $cityDetector;
    }

    public function open(Link $link, Request $request): void
    {
        $meta = $this->parseRequest($request);
        $city = $this->cityDetector->detect($request);

        $this->bus->dispatch(OpenLinkMessage::factory($link, $city, $meta));
    }

    private function parseRequest(Request $request): array
    {
        $userAgent = $request->headers->get('User-Agent');

        return [
            'User-Agent' => $userAgent,
        ];
    }
}