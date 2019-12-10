<?php

namespace App\Domain;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    const ROLE_USER = 'ROLE_USER';

    /** @var int */
    private $id;

    /** @var array */
    private $roles;

    /** @var string */
    private $googleId;

    public function __construct(string $googleId)
    {
        $this->googleId = $googleId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        return [
            self::ROLE_USER
        ];
    }

    public function getPassword() {}

    public function getSalt() {}

    public function getUsername() {}

    public function eraseCredentials() {}

    public function getGoogleId(): string
    {
        return $this->googleId;
    }
}
