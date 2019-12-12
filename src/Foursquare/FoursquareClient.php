<?php

namespace App\Foursquare;

use Psr\Http\Message\ResponseInterface;

interface FoursquareClient
{
    public function searchVenues(FoursquareFilter $filter): ResponseInterface;

    public function getVenueDetails(string $id): ResponseInterface;
}
