<?php

namespace App\Infrastructure\Command;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use App\Scraper\AmazonScraper;
use App\Scraper\Exception\ScrapeException;
use App\Scraper\ScraperFactory;
use App\Scraper\ValueObject\ImportedReviewsCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ScrapeReviewsCommand extends Command
{
    const SOURCES = [
        Review::SOURCE_AMAZON => 'https://google.com',
    ];

    protected static $defaultName = 'app:scrape-reviews';
    protected static string $defaultDescription = 'Scrape DietWater reviews from defined sources';

    public function __construct(private ScraperFactory $scraperFactory, private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Scrape reviews without persistence');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io      = new SymfonyStyle($input, $output);
        $persist = !$input->getOption('dry-run');
        $counter = 0;

        $io->title('DietWater Reviews Importer');
        $io->section('The importer is about to start hearing what our customers say about us');

        foreach (self::SOURCES as $source => $url) {
            $io->block("SOURCE: $source", null, 'fg=black;bg=blue', ' ', true);

            if (!$persist) {
                $io->note('Dry-Run mode: reviews won\'t be stored in the database');
            }

            try {
                $result  = $this->scraperFactory->bySource($source)->scrape();
                $counter += $result->count();

                $io->table(...$result->toTable());

                if ($persist) {
                    $this->persistReviews($result);
                }
            } catch (ScrapeException $e) {
                $io->error(
                    sprintf('An error occurred while trying to scrape reviews from %s: %s', $source, $e->getMessage())
                );
            }
        }

        $io->success(sprintf('%s %d reviews successfully', $persist ? 'Imported' : 'Scraped', $counter));

        return Command::SUCCESS;
    }

    private function persistReviews(ImportedReviewsCollection $reviewsCollection): void
    {
        foreach ($reviewsCollection as $importedReview) {
            $review = Review::fromImportedReview($importedReview);

            $this->entityManager->persist($review);
        }

        $this->entityManager->flush();
    }
}
