<?php

namespace App\Google;

class TokenValidator
{
    /** @var Google_Client */
    private $client;

    public function __construct(\Google_Client $client)
    {
        $this->client = $client;
    }

    /**
     * @see https://developers.google.com/identity/sign-in/web/backend-auth
     * @see https://developers.google.com/identity/protocols/OpenIDConnect#server-flow
     * 
     * @param string $token a JWT token
     * 
     * @return string the authenticated user id
     * 
     * @throws GoogleTokenException
     */
    public function verifyToken(string $token): string
    {
        // @TODO cache JWT to avoid querying google api everytime
        $payload = $this->client->verifyIdToken($token);

        if ($payload) {
            return $payload['sub'];
        } else {
            throw new GoogleTokenException('Invalid token id.');
        }
    }
}