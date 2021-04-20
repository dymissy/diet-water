<?php

namespace App\Tests\Functional\Api;

use App\Tests\ApiTestCase;

class GetReviewsListTest extends ApiTestCase
{
    /**
     * @test
     */
    public function shouldReturnFirstPageWithoutQueryStringParams(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/v1/reviews');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $response = $this->parseJsonResponse($client->getInternalResponse());
        $this->assertCount(2, $response);
        $this->assertEquals(
            [
                'id' => 5,
                'title' => "Review 5",
                'content' => "Lorem ipsum dolor sit amet",
                'rate' => 5,
                'source' => "amazon",
                'author' => "User5 J*** S****",
                'created_at' => "2020-05-05T00:00:00+00:00",
                'imported_at' => "2021-04-21T11:34:31+00:00",
            ],
            $response[0]
        );
        $this->assertEquals(
            [
                'id' => 4,
                'title' => "Review 4",
                'content' => "Lorem ipsum dolor sit amet",
                'rate' => 4,
                'source' => "amazon",
                'author' => "User4 J*** S****",
                'created_at' => "2020-04-04T00:00:00+00:00",
                'imported_at' => "2021-04-21T11:34:31+00:00",
            ],
            $response[1]
        );
    }

    /**
     * @test
     */
    public function shouldReturnValidResponseWithCustomPageAndLimitParams(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/v1/reviews', ['page' => 3, 'limit' => 1]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $response = $this->parseJsonResponse($client->getInternalResponse());
        $this->assertCount(1, $response);
        $this->assertEquals("Review 2", $response[0]['title']);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyResponseWithInvalidPageOrLimitParams(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/v1/reviews', ['page' => 300, 'limit' => 100]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $response = $this->parseJsonResponse($client->getInternalResponse());
        $this->assertCount(0, $response);
        $this->assertEmpty($response);
    }
}