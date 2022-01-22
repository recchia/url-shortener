<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\ShortUrl;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class ShortUrlTest extends ApiTestCase
{
    /**
     * @test
     */
    public function itMustReturnShortUrlCollection(): void
    {
        $response = static::createClient()->request('GET', '/api/short_urls?page=1&itemsPerPage=100&order%5Bhits%5D=desc');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $data = \json_decode($response->getContent(), true);

        $this->assertSame('/api/contexts/ShortUrl', $data['@context']);
        $this->assertSame('/api/short_urls', $data['@id']);
        $this->assertSame('hydra:Collection', $data['@type']);
        $this->assertSame(3, $data['hydra:totalItems']);
        $this->assertCount(3, $data['hydra:member']);
    }

    /**
     * @test
     */
    public function itMustReturnShortUrlItem(): void
    {
        $client = static::createClient();
        $repository = static::getContainer()
            ->get(EntityManagerInterface::class)
            ->getRepository(ShortUrl::class);

        $shortUrls = $repository->findAll();

        foreach ($shortUrls as $shortUrl) {
            $iri = $this->findIriBy(ShortUrl::class, ['id' => $shortUrl->getId()]);
            $response = static::createClient()->request('GET', $iri);

            $this->assertResponseIsSuccessful();
            $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

            $data = \json_decode($response->getContent(), true);

            $this->assertSame('/api/contexts/ShortUrl', $data['@context']);
            $this->assertSame($iri, $data['@id']);
            $this->assertSame('ShortUrl', $data['@type']);
            $this->assertSame($shortUrl->getId(), $data['id']);
            $this->assertSame($shortUrl->getTitle(), $data['title']);
            $this->assertSame($shortUrl->getLongUrl(), $data['longUrl']);
            $this->assertSame('http://example.com/'.$shortUrl->getShortUrl(), $data['shortUrl']);
            $this->assertSame($shortUrl->getHits(), $data['hits']);
        }
    }

    /**
     * @test
     */
    public function itMustCreateShortUrl(): void
    {
        $url = 'https://www.pierorecchia.com/';
        $options = [
            'json' => [
                'longUrl' => $url,
            ],
        ];

        $response = static::createClient()->request('POST', '/api/short_urls', $options);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $data = \json_decode($response->getContent(), true);

        $this->assertSame('/api/contexts/ShortUrl', $data['@context']);
        $this->assertSame('ShortUrl', $data['@type']);
        $this->assertSame(null, $data['title']);
        $this->assertSame($url, $data['longUrl']);
        $this->assertSame(0, $data['hits']);
    }

    /**
     * @test
     */
    public function itMustFailCreatingShortUrlWhenUrlIsInvalid(): void
    {
        $url = 'www.pierorecchia.com/';
        $options = [
            'json' => [
                'longUrl' => $url,
            ],
        ];

        $response = static::createClient()->request('POST', '/api/short_urls', $options);

        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $data = \json_decode($response->getContent(false), true);

        $this->assertSame('/api/contexts/Error', $data['@context']);
        $this->assertSame('hydra:Error', $data['@type']);
        $this->assertSame('An error occurred', $data['hydra:title']);
        $this->assertSame('URL does not have a valid format.', $data['hydra:description']);
    }
}
