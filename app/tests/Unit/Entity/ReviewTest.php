<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Review;
use App\Scraper\ValueObject\ImportedReview;
use PHPUnit\Framework\TestCase;

class ReviewTest extends TestCase
{
    /**
     * @test
     */
    public function shouldInstantiateAReview(): void
    {
        $data = [
            'title'       => 'Title',
            'content'     => 'Description',
            'rate'        => 1,
            'source'      => Review::SOURCE_AMAZON,
            'author'      => "S****** D******",
            'created_at'  => "2021-04-01",
            'imported_at'  => "2021-04-10",
        ];

        $review = Review::create($data);

        $this->assertInstanceOf(Review::class, $review);
        $this->assertEquals(
            [
                'id' => null,
                'title' => 'Title',
                'content' => 'Description',
                'rate' => 1,
                'source' => 'amazon',
                'author' => 'S****** D******',
                'created_at' => '2021-04-01T00:00:00+00:00',
                'imported_at' => '2021-04-10T00:00:00+00:00',
            ],
            $review->jsonSerialize()
        );
    }
    /**
     * @test
     */
    public function shouldInstantiateAReviewFromImporterReviewObject(): void
    {
        $importedReview = new ImportedReview(
            'Title',
            "S****** D******",
            'Description',
            5,
            Review::SOURCE_AMAZON,
            new \DateTimeImmutable("2021-04-01"),
            new \DateTimeImmutable("2021-04-10")
        );

        $review = Review::fromImportedReview($importedReview);

        $this->assertInstanceOf(Review::class, $review);
        $this->assertEquals(
            [
                'id' => null,
                'title' => 'Title',
                'content' => 'Description',
                'rate' => 5,
                'source' => 'amazon',
                'author' => 'S****** D******',
                'created_at' => '2021-04-01T00:00:00+00:00',
                'imported_at' => '2021-04-10T00:00:00+00:00',
            ],
            $review->jsonSerialize()
        );
    }
}
