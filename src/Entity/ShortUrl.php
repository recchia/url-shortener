<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use App\Repository\ShortUrlRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: ShortUrlRepository::class)]
#[UniqueEntity(fields: 'longUrl')]
#[ApiResource(
    collectionOperations: ['get', 'post'],
    itemOperations: ['get'],
    denormalizationContext: ['groups' => ['short-url:write']],
    normalizationContext: ['groups' => ['short-url:read']]
)]
#[ApiFilter(OrderFilter::class, properties: ['hits'])]
class ShortUrl
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['short-url:read'])]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['short-url:read'])]
    private ?string $title;

    #[ORM\Column(type: 'string', length: 255)]
    #[NotBlank]
    #[Groups(['short-url:write', 'short-url:read'])]
    private ?string $longUrl;

    #[ORM\Column(type: 'string', length: 9)]
    #[Groups(['short-url:read'])]
    private ?string $shortUrl;

    #[ORM\Column(type: 'integer')]
    #[Groups(['short-url:read'])]
    private ?int $hits = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLongUrl(): ?string
    {
        return $this->longUrl;
    }

    public function setLongUrl(string $longUrl): self
    {
        $this->longUrl = $longUrl;

        return $this;
    }

    public function getShortUrl(): ?string
    {
        return $this->shortUrl;
    }

    public function setShortUrl(string $shortUrl): self
    {
        $this->shortUrl = $shortUrl;

        return $this;
    }

    public function getHits(): ?int
    {
        return $this->hits;
    }

    public function setHits(int $hits): self
    {
        $this->hits = $hits;

        return $this;
    }
}
