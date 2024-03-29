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

        $totalHistories = $repository->countAllForUser($this->getUser());
        $totalPages     = ceil($totalHistories / HistoryRepository::LIMIT);

        return new JsonResponse($definitions, Response::HTTP_OK, ['Total-Pages' => $totalPages]);
    }

    public function create(Request $request, HistoryRepository $repository): JsonResponse
    {
        if (!$request->request->has('restaurantId') || !$request->request->has('restaurantName')) {
            return new JsonResponse('Bad request', Response::HTTP_BAD_REQUEST);
        }

        $history = new HistoryEntity(
            $this->getUser(),
            $request->request->get('restaurantId'),
            $request->request->get('restaurantName'),
        );

        $repository->save($history);

        return new JsonResponse($history);
    }

    public function delete(string $id, HistoryRepository $repository): JsonResponse
    {
        $history = $repository->find($id);

        if (!$history) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        if ($history->getUser() !== $this->getUser()) {
            return new JsonResponse(null, Response::HTTP_UNAUTHORIZED);
        }

        $repository->delete($id);

        return new JsonResponse($id);
    }
}
