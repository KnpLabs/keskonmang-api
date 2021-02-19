<?php

namespace App\Symfony\EndPoint;

use App\Domain\History as HistoryEntity;
use App\Symfony\JsonDefinition\History as HistoryDefinition;
use App\Symfony\Repository\HistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class History extends Controller
{
    public function list(Request $request, HistoryRepository $repository): JsonResponse
    {
        $definitions = [];
        $histories   = $repository->findAllForUser(
            $this->getUser(), 
            $request->query->getInt('page', 1)
        );

        foreach ($histories as $history) {
            $definitions[] = new HistoryDefinition($history);
        }

        return new JsonResponse($definitions);
    }

    public function create(Request $request, HistoryRepository $repository): JsonResponse
    {
        if (!$request->request->has('restaurantId')) {
            return new JsonResponse('Bad request', Response::HTTP_BAD_REQUEST);
        }

        $history = new HistoryEntity($this->getUser(), $request->request->get('restaurantId'));

        $repository->save($history);

        return new JsonResponse($history);
    }
}
