<?php

namespace App\Tests;

use Facebook\WebDriver\WebDriverDimension;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;

class BrowserTestCase extends PantherTestCase
{
    public function takeScreenshot(Client $client, string $fileName): void
    {
        $baseDir = $_SERVER['PANTHER_SUCCESS_SCREENSHOT_DIR'] ?? null;
        if (!$baseDir) {
            return;
        }

        $fileName = sprintf('%s/%s_%s.png', $baseDir, date('Ymd_His'), $fileName);

        $client->manage()->window()->setSize(new WebDriverDimension(1024, 5000));
        $client->takeScreenshot($fileName);
    }
}