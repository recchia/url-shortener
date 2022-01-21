<?php

namespace App\Contract;

interface UrlShortenerProviderInterface
{
    public function reduceUrl(string $url): string;
}
