<?php

namespace App\Message;

final class RecordHitMessage
{
    private int $shortUrlId = 0;

    public function __construct(int $shortUrlId)
    {
        $this->shortUrlId = $shortUrlId;
    }

    /**
     * @return int
     */
    public function getShortUrlId(): int
    {
        return $this->shortUrlId;
    }
}
