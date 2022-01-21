<?php

namespace App\Message;

final class AddTitleToShortUrlMessage
{
    private int $id = 0;

     public function __construct(int $id)
     {
         $this->id = $id;
     }

    public function getId(): string
    {
        return $this->id;
    }
}
