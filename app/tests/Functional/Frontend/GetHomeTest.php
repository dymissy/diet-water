<?php

namespace App\Tests\Functional\Frontend;


use App\Tests\BrowserTestCase;
use Facebook\WebDriver\WebDriverDimension;

class GetHomeTest extends BrowserTestCase
{
    /**
     * @test
     */
    public function shouldLoadHomePage(): void
    {
        $client = static::createPantherClient(
            [
                '--headless',
                '--no-sandbox',
                '--disable-gpu',
            ]
        );
        $crawler = $client->get('/');

        $crawler->waitFor('.hero-title');
        $this->assertSelectorTextContains('h1.hero-title', 'DietWater');
        $this->assertSelectorTextContains('p.hero-paragraph', 'The first water that makes you lose weight.');
        $this->assertSelectorTextContains('p.hero-paragraph', 'DietWater is the perfect weight loss beverage.');

        //wait for async reviews loading
        $crawler->waitFor('.review-body');
        $this->assertSelectorTextContains('.review-5', 'Review 5');
        $this->assertSelectorTextContains('.review-5', 'User5 J*** S**** on 5/5/2020 said:');
        $this->assertSelectorTextContains('.review-5', 'Lorem ipsum dolor sit amet');
        $this->assertSelectorTextContains('.review-5', 'source: Amazon - rate: 5/5');

        $this->assertSelectorTextContains('.review-4', 'Review 4');
        $this->assertSelectorTextContains('.review-4', 'User4 J*** S**** on 4/4/2020 said:');
        $this->assertSelectorTextContains('.review-4', 'Lorem ipsum dolor sit amet');
        $this->assertSelectorTextContains('.review-4', 'source: Amazon - rate: 4/5');

        //load more reviews
        $crawler->clickLink('Load More');
        $crawler->waitFor('.review-3');
        $this->assertSelectorTextContains('.review-3', 'Review 3');
        $this->assertSelectorTextContains('.review-3', 'User3 J*** S**** on 3/3/2020 said:');
        $this->assertSelectorTextContains('.review-3', 'Lorem ipsum dolor sit amet');
        $this->assertSelectorTextContains('.review-3', 'source: Amazon - rate: 3/5');

        $this->assertSelectorTextContains('.review-2', 'Review 2');
        $this->assertSelectorTextContains('.review-2', 'User2 J*** S**** on 2/2/2020 said:');
        $this->assertSelectorTextContains('.review-2', 'Lorem ipsum dolor sit amet');
        $this->assertSelectorTextContains('.review-2', 'source: Amazon - rate: 2/5');

        //load last reviews
        $crawler->clickLink('Load More');
        $crawler->waitFor('.review-1');
        $this->assertSelectorTextContains('.review-1', 'Review 1');
        $this->assertSelectorTextContains('.review-1', 'User1 J*** S**** on 1/1/2020 said:');
        $this->assertSelectorTextContains('.review-1', 'Lorem ipsum dolor sit amet');
        $this->assertSelectorTextContains('.review-1', 'source: Amazon - rate: 1/5');

        //"Load More" button is no longer available
        $this->assertSelectorIsNotVisible('.reviews-more');

        //save screenshot
        $this->takeScreenshot($client, 'homepage');

        shell_exec('ls -la /var/www/dietwater/var/success-screenshots/');
    }
}