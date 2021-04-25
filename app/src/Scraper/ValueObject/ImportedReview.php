<?php

namespace App\Scraper\ValueObject;

class ImportedReview
{
    public function __construct(
        public string $title,
        public string $author,
        public string $content,
        public int $rate,
        public string $source,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $importedAt
    ) {
        $this->author = $this->obfuscate($author);
    }

    private function obfuscate(string $author): string
    {
        $authorChunks = array_map(
            function (string $chunk) {
                return $chunk[0] . str_repeat("*", strlen($chunk) - 1);
            },
            explode(" ", $author)
        );

        return implode(" ", $authorChunks);
    }
}