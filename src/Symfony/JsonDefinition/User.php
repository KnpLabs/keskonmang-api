<?php

namespace App\Symfony\JsonDefinition;

use App\Domain\User as UserEntity;

class User
{
    /** @var int */
    public $id;

    /** @var string */
    public $googleId;

    public function __construct(UserEntity $user)
    {
        $this->id = $user->getId();
        $this->googleId = $user->getGoogleId();
    }
}