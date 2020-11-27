<?php

namespace App\Symfony\JsonDefinition;

use App\Domain\User as UserEntity;

class User
{
    public int $id;

    public string $googleId;

    public function __construct(UserEntity $user)
    {
        $this->id = $user->getId();
        $this->googleId = $user->getGoogleId();
    }
}
