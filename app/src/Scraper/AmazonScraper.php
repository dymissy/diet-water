<?php

namespace App\Scraper;

use App\Entity\Review;
use App\Repository\ReviewSourcesRepository;
use App\Scraper\Exception\ScrapeException;
use App\Scraper\ValueObject\ImportedReview;
use App\Scraper\ValueObject\ImportedReviewsCollection;
use Exception;
use Facebook\WebDriver\Exception\TimeoutException;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Crawler;

class AmazonScraper implements Scraper
{
    public function __construct(private ReviewSourcesRepository $repository, private Client $client)
    {
    }

    public function scrape(): ImportedReviewsCollection
    {
        $productUrl = $this->repository->find(Review::SOURCE_AMAZON);
        $collection = new ImportedReviewsCollection();

        try {
            $this->client->get($productUrl);

            $crawler = $this->client->waitFor('#cm_cr-review_list');
            $crawler->filter('#cm_cr-review_list .a-section.review.aok-relative')->each(
                function (Crawler $item) use ($collection) {
                    $review = $this->parseReview(
                        $item->filter('.review-title')->getText(),
                        $item->filter('.review-text-content')->getText(),
                        $item->filter('.a-profile-name')->getText(),
                        $item->filter('.review-date')->getText(),
                        $item->filter('.review-rating')->getAttribute('class')
                    );

                    $collection->add($review);
                }
            );
        } catch (TimeoutException $e) {
            throw new ScrapeException('Timeout');
        } catch (Exception $e) {
            throw new ScrapeException($e->getMessage());
        }

        return $collection;
    }

    private function parseReview(
        string $title,
        string $content,
        string $author,
        string $date,
        string $rate
    ): ImportedReview {
        preg_match('/(\w+) (\d+), (\d+)$/', $date, $matches);
        $date = new \DateTimeImmutable($matches[0]);

        preg_match('/a-star-(\d)/', $rate, $matches);
        $rate = (int)$matches[1];

        return new ImportedReview(
            $title, $author, $content, $rate, Review::SOURCE_AMAZON, $date, new \DateTimeImmutable()
        );
    }
}