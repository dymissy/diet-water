<?php

namespace App\Tests\Unit\Scraper;

use App\Repository\ReviewSourcesRepository;
use App\Scraper\AmazonScraper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Panther\Client;

class AmazonScraperTest extends TestCase
{
    private AmazonScraper $scraper;

    protected function setUp(): void
    {
        $client        = Client::createChromeClient();
        $repository    = new ReviewSourcesRepository("http://webserver/debug/scraping/amazon-review");
        $this->scraper = new AmazonScraper($repository, $client);
    }

    /**
     * @test
     */
    public function shouldScrapeReviews(): void
    {
        $collection = $this->scraper->scrape();

        $this->assertCount(10, $collection);
        $this->assertEquals("D***********", $collection->get(0)->author);
        $this->assertEquals("J* R******", $collection->get(1)->author);
        $this->assertEquals("A*", $collection->get(2)->author);
        $this->assertEquals("A******* H*****", $collection->get(3)->author);
        $this->assertEquals("W******* C*********", $collection->get(4)->author);
        $this->assertEquals("M**", $collection->get(5)->author);
        $this->assertEquals("T********", $collection->get(6)->author);
        $this->assertEquals("K*** L***", $collection->get(7)->author);
        $this->assertEquals("J*** N", $collection->get(8)->author);
        $this->assertEquals("M** D", $collection->get(9)->author);
    }
}
