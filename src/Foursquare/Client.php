<?php

namespace App\Foursquare;

use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;

class Client implements FoursquareClient
{
    /** @var string */
    private $clientId;

    /** @var string */
    private $clientSecret;

    /** @var string YYYYMMDD formated date string*/
    private $apiVersion;

    public function __construct(
        string $clientId,
        string $clientSecret,
        string $apiVersion
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->apiVersion = $apiVersion;
    }

    public function request(string $method, string $endpoint, string $filters = ''): ResponseInterface
    {
        $client = new HttpClient();

        $uri = \sprintf(
            'https://api.foursquare.com/v2%s?client_id=%s&client_secret=%s&v=%s%s',
            $endpoint,
            $this->clientId,
            $this->clientSecret,
            $this->apiVersion,
            $filters
        );

        return $client->request($method, $uri);
    }

    /**
     * {@inheritdoc}
     */
    public function searchVenues(FoursquareFilter $filter): ResponseInterface
    {
        return $this->request(
            'GET',
            '/venues/search',
            $filter->toQueryParameters()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getVenueDetails(string $id): ResponseInterface
    {
        return $this->request(
            'GET',
            \sprintf('/venues/%s', $id)
        );
    }
}
