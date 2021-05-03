<?php

namespace App\Repository;

use App\Entity\Review;

class ReviewSourcesRepository
{
    /** @var array */
    private array $sources;

    public function __construct(string $amazonUrl)
    {
        $this->sources = [
            Review::SOURCE_AMAZON => $amazonUrl,
        ];
    }

    public function findAll(): array
    {
        return $this->sources;
    }

    public function find(string $source): ?string
    {
        return $this->sources[$source] ?? null;
    }
}