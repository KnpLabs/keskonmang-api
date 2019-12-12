<?php

namespace App\Symfony\EndPoint;

use App\Foursquare\FoursquareClient;
use App\Symfony\Filter\Venue as VenueFilter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Venue extends Controller
{
    /** @var FoursquareClient */
    private $client;

    public function __construct(
        FoursquareClient $client
    ) {
        $this->client = $client;
    }

    public function search(Request $request): JsonResponse
    {
        try {
            $filter = new VenueFilter($request);
            $response = $this->client->searchVenues($filter);
        } catch(\Exception $e) {
            return new JsonResponse(Response::HTTP_BAD_REQUEST, $response->getBody());
        }

        return new JsonResponse(\json_decode($response->getBody()));
    }
}
