<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Response;

class ApiTestCase extends WebTestCase
{
    public function parseJsonResponse(Response $response): array
    {
        return json_decode($response->getContent(), true);
    }
}