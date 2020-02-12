<?php declare(strict_types=1);

namespace Media\Messenger\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Location\Entity\City;
use Media\Entity\Link;
use Media\Entity\LinkStatistic;
use Media\Messenger\Message\OpenLinkMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class OpenLinkHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(OpenLinkMessage $message): void
    {
        $link = $this->getLink($message);
        $city = $this->getCity($message);

        $entity = new LinkStatistic($link, $city, $message->getMeta());
        $this->em->persist($entity);
        $this->em->flush();
    }

    protected function getLink(OpenLinkMessage $message): Link
    {
        return $this->em->find(Link::class, $message->getId());
    }

    protected function getCity(OpenLinkMessage $message): ?City
    {
        return ($id = $message->getCityId()) ? $this->em->find(City::class, $id) : null;
    }
}
