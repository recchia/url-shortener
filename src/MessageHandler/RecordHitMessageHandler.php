<?php

namespace App\MessageHandler;

use App\Message\RecordHitMessage;
use App\Repository\ShortUrlRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class RecordHitMessageHandler implements MessageHandlerInterface
{
    private ShortUrlRepository $shortUrlRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(ShortUrlRepository $shortUrlRepository, EntityManagerInterface $entityManager)
    {
        $this->shortUrlRepository = $shortUrlRepository;
        $this->entityManager = $entityManager;
    }
    public function __invoke(RecordHitMessage $message)
    {
        $shortUrl = $this->shortUrlRepository->find($message->getShortUrlId());
        $shortUrl->setHits($shortUrl->getHits() + 1);
        $this->entityManager->flush();
    }
}
