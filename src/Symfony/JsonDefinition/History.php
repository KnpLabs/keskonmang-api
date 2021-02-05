<?php

namespace App\Symfony\JsonDefinition;

use App\Domain\History as HistoryEntity;

class History
{
    public int $id;

    public string $restaurantId;

    public string $createdAt;

    public function __construct(HistoryEntity $history)
    {
        $this->id           = $history->getId();
        $this->restaurantId = $history->getRestaurantId();
        $this->createdAt    = $history->getCreatedAt()->format('c');
    }
}
