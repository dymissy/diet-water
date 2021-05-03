<?php

namespace App\Tests\Unit\Scraper\ValueObject;

use App\Entity\Review;
use App\Scraper\ValueObject\ImportedReview;
use PHPUnit\Framework\TestCase;

class ImportedReviewTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreateValidInstanceWithAuthorObfuscated(): void
    {
        $importedReview = new ImportedReview(
            'Title',
            "Simone D'Amico",
            'Description',
            5,
            Review::SOURCE_AMAZON,
            new \DateTimeImmutable("2021-04-01"),
            new \DateTimeImmutable("2021-04-10")
        );

        $this->assertEquals('Title', $importedReview->title);
        $this->assertEquals('S***** D******', $importedReview->author);
        $this->assertEquals('Description', $importedReview->content);
        $this->assertEquals(5, $importedReview->rate);
        $this->assertEquals(Review::SOURCE_AMAZON, $importedReview->source);
        $this->assertEquals(new \DateTimeImmutable("2021-04-01"), $importedReview->createdAt);
        $this->assertEquals(new \DateTimeImmutable("2021-04-10"), $importedReview->importedAt);
    }
}
