<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Contract\UrlShortenerProviderInterface;
use App\Entity\ShortUrl;
use App\Message\AddTitleToShortUrlMessage;
use ErrorException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class ShortUrlDataPersister implements ContextAwareDataPersisterInterface
{

    private ContextAwareDataPersisterInterface $decorated;
    private UrlShortenerProviderInterface $urlShortenerProvider;
    private MessageBusInterface $messageBus;

    public function __construct(
        ContextAwareDataPersisterInterface $decorated,
        UrlShortenerProviderInterface $urlShortenerProvider,
        MessageBusInterface $messageBus
    )
    {
        $this->decorated = $decorated;
        $this->urlShortenerProvider = $urlShortenerProvider;
        $this->messageBus = $messageBus;
    }

    public function supports($data, array $context = []): bool
    {
        return $this->decorated->supports($data, $context);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ErrorException
     */
    public function persist($data, array $context = [])
    {
        if ($data instanceof ShortUrl && ($context['collection_operation_name'] ?? null) === 'post') {
            $shortUrl = $this->urlShortenerProvider->reduceUrl($data->getLongUrl());
            $data->setShortUrl($shortUrl);
        }

        $result = $this->decorated->persist($data, $context);

        $this->messageBus->dispatch(new AddTitleToShortUrlMessage($result->getId()));

        return $result;
    }

    public function remove($data, array $context = [])
    {
        return $this->decorated->remove($data, $context);
    }
}
