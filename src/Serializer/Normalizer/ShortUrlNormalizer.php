<?php

namespace App\Serializer\Normalizer;

use App\Entity\ShortUrl;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

class ShortUrlNormalizer implements ContextAwareNormalizerInterface, CacheableSupportsMethodInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'SHORT_URL_NORMALIZER_ALREADY_CALLED';
    private UrlGeneratorInterface $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function normalize($object, $format = null, array $context = []): array
    {
        $context[self::ALREADY_CALLED] = true;

        $data = $this->normalizer->normalize($object, $format, $context);

        $data['shortUrl'] = $this->router->generate('redirection', ['urlId' => $data['shortUrl']], UrlGeneratorInterface::ABSOLUTE_URL);

        return $data;
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof ShortUrl;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return false;
    }
}
