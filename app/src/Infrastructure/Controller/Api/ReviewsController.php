<?php

namespace App\Infrastructure\Controller\Api;

use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1', name: 'api_')]
class ReviewsController extends AbstractController
{
    #[Route('/reviews', name: 'reviews')]
    public function list(Request $request, ReviewRepository $repository): JsonResponse
    {
        $limit = $request->get('limit', 2);
        $page = $request->get('page', 0);
        $reviews = $repository->findBy([], ['createdAt' => 'DESC'], $limit, $page * $limit);

        return new JsonResponse($reviews);
    }
}
