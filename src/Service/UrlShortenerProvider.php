<?php

namespace App\Service;

use App\Contract\UrlShortenerProviderInterface;
use App\Exception\UrlShortenerProviderException;
use Delight\Random\Random;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UrlShortenerProvider implements UrlShortenerProviderInterface
{

    private HttpClientInterface $httpClient;
    private ValidatorInterface $validator;

    public function __construct(HttpClientInterface $httpClient, ValidatorInterface $validator)
    {
        $this->httpClient = $httpClient;
        $this->validator = $validator;
    }

    public function reduceUrl(string $url): string
    {
        if (!$this->isValidUrlFormat($url)) {
            throw new UrlShortenerProviderException('URL does not have a valid format.');
        }

        if (!$this->urlExists($url)) {
            throw new UrlShortenerProviderException('URL does not appear to exist.');
        }

        return $this->generateShortCode();
    }

    private function urlExists(string $url): bool
    {
        $response = $this->httpClient->request('GET', $url);

        return ($response->getStatusCode() === Response::HTTP_OK);
    }

    private function isValidUrlFormat(string $url): bool
    {
        $errors = $this->validator->validate($url, [new NotBlank(), new Url()]);

        return 0 === $errors->count();
    }

    protected function generateShortCode(): string
    {
        return Random::alphanumericString(random_int(5,9));
    }
}
