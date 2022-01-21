<?php

namespace App\Contract;

interface TitleResolverInterface
{
    public function resolveTitle(string $longUrl): string;
}
