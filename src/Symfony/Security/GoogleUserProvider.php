<?php

namespace App\Symfony\Security;

use App\Domain\User;
use App\Google\TokenValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class GoogleUserProvider
{
    /** @var EntityManagerInterface */
    private $orm;

    /** @var TokenValidator */
    private $tokenValidator;

    public function __construct(
        EntityManagerInterface $orm,
        TokenValidator $tokenValidator
    ) {
        $this->orm = $orm;
        $this->tokenValidator = $tokenValidator;
    }
    
    public function getUser(string $token): User
    {
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
}