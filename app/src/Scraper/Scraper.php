<?php

namespace App\Scraper;

use App\Scraper\ValueObject\ImportedReviewsCollection;

interface Scraper
{
    public function scrape(): ImportedReviewsCollection;
}