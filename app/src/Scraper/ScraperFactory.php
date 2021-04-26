<?php

namespace App\Scraper;

use App\Entity\Review;
use App\Repository\ReviewSourcesRepository;
use InvalidArgumentException;
use Symfony\Component\Panther\Client;

class ScraperFactory
{
    private Client $client;

    public function __construct(private ReviewSourcesRepository $repository)
    {
        $options = [
            '--headless',
            '--window-size=1200,1100',
            '--no-sandbox',
            '--disable-gpu'
        ];

        $this->client = Client::createChromeClient(null, null, $options);
    }

    public function bySource(string $source): Scraper
    {
        if ($source === Review::SOURCE_AMAZON) {
            return new AmazonScraper($this->repository, $this->client);
        }

        throw new InvalidArgumentException("$source source is not available");
    }
}