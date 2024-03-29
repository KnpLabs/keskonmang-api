<?php

namespace spec\App\Symfony\JsonDefinition;

use App\Domain\History;
use App\Symfony\JsonDefinition\History as HistoryDefinition;
use PhpSpec\ObjectBehavior;

class HistorySpec extends ObjectBehavior
{
    function let(History $history)
    {
        $history->getId()->willReturn(1);
        $history->getCreatedAt()->willReturn(new \DateTime('11/12/2019 00:00:00'));
        $history->getRestaurantId()->willReturn('123');
        $history->getRestaurantName()->willReturn('Restaurant name');

        $this->beConstructedWith($history);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(HistoryDefinition::class);
    }

    function it_has_an_id()
    {
        $this->id->shouldBe(1);
    }

    function it_has_restaurant_id()
    {
        $this->restaurantId->shouldBe('123');
    }

    function it_has_restaurant_name()
    {
        $this->restaurantName->shouldBe('Restaurant name');
    }

    function it_has_a_created_at()
    {
        $this->createdAt->shouldBeLike((new \DateTime('11/12/2019 00:00:00'))->format('c'));
    }
}
