<?php

namespace App\Symfony\EndPoint;

use App\Yelp\YelpClient;
use App\Symfony\Filter\Venue as VenueFilter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Venue extends Controller
{
    /** @var YelpClient */
    private $client;

    public function __construct(YelpClient $client) {
        $this->client = $client;
    }

    public function search(Request $request): JsonResponse
    {
        try {
            $filter = new VenueFilter($request);
            $response = $this->client->searchVenues($filter);
        } catch(\Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $body = \json_decode($response->getBody());

        return new JsonResponse($body->businesses);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        try {
            $response = $this->client->getVenueDetails($id);
        } catch (\Exception $e) {
            return new JsonReponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $body = \json_decode($response->getBody());

        return new JsonResponse($body->businesses);
    }
}
