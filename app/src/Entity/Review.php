<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 */
class Review implements JsonSerializable
{
    public const SOURCE_AMAZON = 'amazon';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $source;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $rate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $importedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'rate' => $this->rate,
            'source' => $this->source,
            'author' => $this->author,
            'created_at' => $this->createdAt->format('c'),
            'imported_at' => $this->importedAt->format('c'),
        ];
    }

    public static function create(array $data): self
    {
        $data = new ParameterBag($data);

        $review = new self();
        $review->title = $data->get('title');
        $review->content = $data->get('content');
        $review->rate = $data->get('rate');
        $review->source = $data->get('source');
        $review->author = $data->get('author');
        $review->createdAt = new DateTimeImmutable($data->get('created_at'));
        $review->importedAt = new DateTimeImmutable($data->get('imported_at'));

        return $review;
    }
}
