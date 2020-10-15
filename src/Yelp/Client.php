<?php

namespace App\Yelp;

use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;

class Client implements YelpClient
{
    /** @var string */
    private $apikey;

    public function __construct(string $apikey)
    {
        $this->apikey = $apikey;
    }

    public function request(string $method, string $endpoint, string $filters = ''): ResponseInterface
    {
        $client = new HttpClient();

        $uri = \sprintf(
            'https://api.yelp.com/v3%s?%s',
            $endpoint,
            $filters
        );

        return $client->request($method, $uri, [
            'headers' => [
                'Authorization' => \sprintf('Bearer %s', $this->apikey)
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function searchRestaurants(YelpFilter $filter): ResponseInterface
    {
        return $this->request(
            'GET',
            '/businesses/search',
            $filter->toQueryParameters()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getRestaurantDetails(string $id): ResponseInterface
    {
        return $this->request(
            'GET',
            \sprintf('/businesses/%s', $id)
        );
    }
}
