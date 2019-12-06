<?php

namespace App\Symfony\Security;

use App\Domain\User;
use App\Google\TokenValidator;
use Doctrine\ORM\EntityManagerInterface;
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
    private $orm;

    private $tokenValidator;

    public function __construct(
        EntityManagerInterface $orm,
        TokenValidator $tokenValidator
    ) {
        $this->orm = $orm;
        $this->tokenValidator = $tokenValidator;
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

    // @TODO create a provider service to do this and improve testability
    public function getUser($token, UserProviderInterface $userProvider)
    {        
        if (null === $token) {
            return;
        }

        // verify JWT over google
        $googleSub = $this->tokenValidator->verifyToken($token);

        $user = $this
            ->orm
            ->getRepository(User::class)
            ->findOneBy(['googleId' => $googleSub])
        ;

        // if user dont exist, create it in the DB
        if(!$user) {
            $user = new User($googleSub);
            $this->orm->persist($user);
            $this->orm->flush();
        }

        return $user;
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