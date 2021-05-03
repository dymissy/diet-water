<?php

namespace App\Scraper\ValueObject;

use Doctrine\Common\Collections\ArrayCollection;

class ImportedReviewsCollection extends ArrayCollection
{
    public function toTable(): array
    {
        return [
            ['Title', 'Author', 'Rate', 'Date'],
            array_map(function(ImportedReview $review) {
                return [
                    substr($review->title, 0, 50),
                    $review->author,
                    $review->rate,
                    $review->createdAt->format('Y-m-d')
                ];
            }, $this->getIterator()->getArrayCopy())
        ];
    }
}