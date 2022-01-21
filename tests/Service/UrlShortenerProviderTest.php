<?php

namespace App\Tests\Service;

use App\Exception\UrlShortenerProviderException;
use App\Service\UrlShortenerProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class UrlShortenerProviderTest extends TestCase
{
    /**
     * @test
     * @throws UrlShortenerProviderException
     */
    public function itCanReduceUrl(): void
    {
        $url = 'https://www.pierorecchia.com';
        $httpClient = $this->createMock(HttpClientInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(200);
        $httpClient->method('request')->willReturn($response);
        $validator = Validation::createValidator();
        $urlShortenerProvider = new UrlShortenerProvider($httpClient, $validator);

        $shortUrl = $urlShortenerProvider->reduceUrl($url);

        $this->assertIsString($shortUrl, 'Is a string');
        $this->assertGreaterThanOrEqual(5, strlen($shortUrl));
        $this->assertLessThanOrEqual(9, strlen($shortUrl));
    }

    /**
     * @test
     */
    public function itFailForInvalidUrl(): void
    {
        $this->expectException(UrlShortenerProviderException::class);

        $url = '';
        $httpClient = $this->createMock(HttpClientInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(200);
        $httpClient->method('request')->willReturn($response);
        $validator = Validation::createValidator();
        $urlShortenerProvider = new UrlShortenerProvider($httpClient, $validator);

        $shortUrl = $urlShortenerProvider->reduceUrl($url);
    }

    /**
     * @test
     * @throws UrlShortenerProviderException
     */
    public function itFailWhenUrlNotWork(): void
    {
        $this->expectException(UrlShortenerProviderException::class);

        $url = 'https://anything.com';
        $httpClient = $this->createMock(HttpClientInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(404);
        $httpClient->method('request')->willReturn($response);
        $validator = Validation::createValidator();
        $urlShortenerProvider = new UrlShortenerProvider($httpClient, $validator);

        $shortUrl = $urlShortenerProvider->reduceUrl($url);
    }
}
