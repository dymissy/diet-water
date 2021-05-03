<?php

namespace App\Tests\Unit\Scraper\ValueObject;

use App\Entity\Review;
use App\Scraper\ValueObject\ImportedReview;
use App\Scraper\ValueObject\ImportedReviewsCollection;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ImportedReviewsCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function shouldContainsValidElements(): void
    {
        $importedReview = new ImportedReview(
            'Title',
            "Simone D'Amico",
            'Description',
            5,
            Review::SOURCE_AMAZON,
            new DateTimeImmutable("2021-04-01"),
            new DateTimeImmutable("2021-04-10")
        );

        $collection = new ImportedReviewsCollection();
        $collection->add($importedReview);

        $this->assertCount(1, $collection);
        $this->assertEquals('S***** D******', $collection->first()->author);
    }

    /**
     * @test
     */
    public function shouldReturnValidTable(): void
    {
        $importedReview = new ImportedReview(
            'Title',
            "Simone D'Amico",
            'Description',
            5,
            Review::SOURCE_AMAZON,
            new DateTimeImmutable('2021-04-01'),
            new DateTimeImmutable('2021-04-10')
        );

        $collection = new ImportedReviewsCollection();
        $collection->add($importedReview);

        $this->assertEquals(
            [
                ['Title', 'Author', 'Rate', 'Date'],
                [['Title', 'S***** D******', 5, '2021-04-01']],
            ],
            $collection->toTable()
        );
    }
}
