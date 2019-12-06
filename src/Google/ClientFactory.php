<?php

namespace App\Google;

final class ClientFactory
{
    public static function create(string $clientId): \Google_Client
    {
        // ugly hack 
        // @see https://github.com/googleapis/google-api-php-client/pull/828
        $jwt = new \Firebase\JWT\JWT;
        $jwt::$leeway += 180;

        return new \Google_Client([
            'client_id' => $clientId,
            'jwt' => $jwt,
        ]);
    }    
}
