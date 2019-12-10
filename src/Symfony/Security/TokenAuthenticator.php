<?php

namespace App\Symfony\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $userProvider;

    public function __construct(
        GoogleUserProvider $userProvider
    ) {
        $this->userProvider = $userProvider;
    }

    public function supports(Request $request)
    {
        return $request->headers->has('Authorization');
    }

    public function getCredentials(Request $request): string
    {     
        $authorization = $request->headers->get('Authorization');
        
        return \str_replace('Bearer ', '', $authorization);
    }

    public function getUser($token, UserProviderInterface $userProvider)
    {        
        if (null === $token) {
            return;
        }

        return $this->userProvider->getUser($token);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {   
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
