<?php

namespace App\Tests\Unit\Repository;

use App\Entity\Review;
use App\Repository\ReviewSourcesRepository;
use PHPUnit\Framework\TestCase;

class ReviewSourcesRepositoryTest extends TestCase
{
    private ReviewSourcesRepository $repository;

    protected function setUp(): void
    {
        $this->repository = new ReviewSourcesRepository('https://www.amazon.it/product');
    }

    /**
     * @test
     */
    public function shouldReturnAllSources(): void
    {
        $this->assertEquals([Review::SOURCE_AMAZON => 'https://www.amazon.it/product'], $this->repository->findAll());
    }

    /**
     * @test
     */
    public function shouldReturnSpecificSource(): void
    {
        $this->assertEquals('https://www.amazon.it/product', $this->repository->find(Review::SOURCE_AMAZON));
    }

    /**
     * @test
     */
    public function shouldReturnNullWhenSourceDoesNotExist(): void
    {
        $this->assertNull($this->repository->find('foobar'));
    }
}
