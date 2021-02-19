<?php

namespace App\Domain;

class History
{
    private int $id;
    private User $user;
    private string $restaurantId;
    private \DateTimeInterface $createdAt;

    public function __construct(User $user, string $restaurantId)
    {
        $this->user         = $user;
        $this->restaurantId = $restaurantId;
        $this->createdAt    = new \DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getRestaurantId(): string
    {
        return $this->restaurantId;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}
