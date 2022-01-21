<?php

namespace App\Service;

use App\Contract\TitleResolverInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TitleResolver implements TitleResolverInterface
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function resolveTitle(string $longUrl): string
    {
        $response = $this->httpClient->request('GET', $longUrl);

        try {
            $crawler = new Crawler($response->getContent());
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|TransportExceptionInterface|ServerExceptionInterface $e) {
            throw new ErrorException($e->getMessage());
        }

        return $crawler->filter('head > title')->text('No title');
    }
}
