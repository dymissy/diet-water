<?php

namespace App\Infrastructure\Controller\Frontend\Debug;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SourcesController extends AbstractController
{
    #[Route('/debug/scraping/amazon-review', name: 'debug_source_amazon')]
    public function amazon(): Response
    {
        return $this->render('debug/amazon.html.twig');
    }
}
