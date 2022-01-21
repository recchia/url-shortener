<?php

namespace App\MessageHandler;

use App\Contract\TitleResolverInterface;
use App\Entity\ShortUrl;
use App\Message\AddTitleToShortUrlMessage;
use Doctrine\ORM\EntityManagerInterface;
use ErrorException;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class AddTitleToShortUrlMessageHandler implements MessageHandlerInterface
{
    private HttpClientInterface $httpClient;
    private EntityManagerInterface $entityManager;
    private TitleResolverInterface $titleResolver;

    public function __construct(TitleResolverInterface $titleResolver, EntityManagerInterface $entityManager)
    {
        $this->titleResolver = $titleResolver;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws TransportExceptionInterface|ErrorException
     */
    public function __invoke(AddTitleToShortUrlMessage $message)
    {
        $shortUrl = $this->entityManager->getRepository(ShortUrl::class)->find($message->getId());
        $shortUrl->setTitle($this->titleResolver->resolveTitle($shortUrl->getLongUrl()));
        $this->entityManager->flush();
    }
}
