<?php

namespace App\Symfony\Security;

use App\Domain\User;
use App\Google\TokenValidator;
use Doctrine\ORM\EntityManagerInterface;

class GoogleUserProvider
{
    private EntityManagerInterface $orm;
    private TokenValidator $tokenValidator;

    public function __construct(
        EntityManagerInterface $orm,
        TokenValidator $tokenValidator
    ) {
        $this->orm            = $orm;
        $this->tokenValidator = $tokenValidator;
    }
    
    public function getUser(string $token): User
    {
        $googleSub = $this->tokenValidator->verifyToken($token);

        $user = $this
            ->orm
            ->getRepository(User::class)
            ->findOneBy(['googleId' => $googleSub])
        ;

        if(!$user) {
            $user = new User($googleSub);
            $this->orm->persist($user);
            $this->orm->flush();
        }        

        return $user;
    }
}
