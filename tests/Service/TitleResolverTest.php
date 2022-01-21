<?php

namespace App\Tests\Service;

use App\Service\TitleResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class TitleResolverTest extends TestCase
{
    public function testResolveTitle(): void
    {
        $title = 'Test Page';
        $content = '<html><head><title>'.$title.'</title></head></html>';
        $url = 'https://www.pierorecchia.com';
        $httpClient = $this->createMock(HttpClientInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getContent')->willReturn($content);
        $httpClient->method('request')->willReturn($response);

        $titleResolver = new TitleResolver($httpClient);

        $this->assertSame($title, $titleResolver->resolveTitle($url));
    }

    public function testDefaultTitleWhenNoExist(): void
    {
        $content = '<html><head></head></html>';
        $url = 'https://www.pierorecchia.com';
        $httpClient = $this->createMock(HttpClientInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getContent')->willReturn($content);
        $httpClient->method('request')->willReturn($response);

        $titleResolver = new TitleResolver($httpClient);

        $this->assertSame('No title', $titleResolver->resolveTitle($url));
    }
}
