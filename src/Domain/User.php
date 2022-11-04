<?php

namespace App\Domain;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    const ROLE_USER = 'ROLE_USER';

    private int $id;
    private array $roles;
    private string $googleId;

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

    public function getGoogleId(): string
    {
        return $this->googleId;
    }

    public function getPassword() {}

    public function getSalt() {}

    public function getUsername() {}

    public function eraseCredentials() {}
}
