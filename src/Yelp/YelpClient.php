<?php

namespace App\Yelp;

use Psr\Http\Message\ResponseInterface;

interface YelpClient
{
    public function searchRestaurants(YelpFilter $filter): ResponseInterface;

    public function getVenueDetails(string $id): ResponseInterface;
}
