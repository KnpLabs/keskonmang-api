<?php

namespace App\Symfony\EndPoint;

use App\Symfony\Filter\Restaurant as RestaurantFilter;
use App\Symfony\JsonDefinition\Restaurant as RestaurantDefinition;
use App\Yelp\YelpClient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Restaurant extends Controller
{
    private YelpClient $yelpClient;

    public function __construct(YelpClient $yelpClient)
    {
        $this->yelpClient = $yelpClient;
    }

    public function search(Request $request): JsonResponse
    {
        try {
            $filter   = new RestaurantFilter($request);
            $response = $this->yelpClient->searchRestaurants($filter);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $body = \json_decode($response->getBody());

        return new JsonResponse($body->businesses);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        try {
            $response = $this->yelpClient->getRestaurantDetails($id);
        } catch (\Exception $e) {
            return new JsonReponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $body = \json_decode($response->getBody());

        return new JsonResponse(new RestaurantDefinition($body));
    }
}
