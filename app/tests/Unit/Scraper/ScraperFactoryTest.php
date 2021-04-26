<?php

namespace App\Tests\Unit\Scraper;

use App\Entity\Review;
use App\Repository\ReviewSourcesRepository;
use App\Scraper\AmazonScraper;
use App\Scraper\Clientss;
use App\Scraper\ScraperFactory;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ScraperFactoryTest extends TestCase
{
    private ScraperFactory $factory;

    protected function setUp(): void
    {
        $repository = new ReviewSourcesRepository('https://amazon.it');
        $this->factory = new ScraperFactory($repository);
    }

    /**
     * @test
     */
    public function shouldReturnValidInstanceBySource(): void
    {
        $scraper = $this->factory->bySource(Review::SOURCE_AMAZON);
        $this->assertInstanceOf(AmazonScraper::class, $scraper);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenSourceIsNotAvailable(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('foo source is not available');

        $this->factory->bySource('foo');
    }
}
