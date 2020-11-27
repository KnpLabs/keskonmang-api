<?php

namespace spec\App\Symfony\Filter;

use App\Symfony\Filter\Restaurant;
use Symfony\Component\HttpFoundation\Request;
use PhpSpec\ObjectBehavior;

class RestaurantSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(new Request());
        $this->shouldHaveType(Restaurant::class);
    }

    function it_create_query_parameters()
    {
        $request = new Request([
            'location'   => 'a location',
            'radius'     => 5000,
            'categories' => ['pizza', 'wok'],
            'prices' => [2, 3],
        ]);

        $this->beConstructedWith($request);

        $this->toQueryParameters()->shouldReturn(
            '&location=a location&categories=pizza,wok&radius=5000&price=2,3&limit=50&open_now=1'
        );
    }

    function it_create_query_with_minimum_required_parameters()
    {
        $request = new Request(['location' => 'a location']);

        $this->beConstructedWith($request);

        $this->toQueryParameters()->shouldReturn(
            '&location=a location&categories=restaurants&radius=2000&limit=50&open_now=1'
        );
    }

    function it_does_not_create_query_parameters_if_location_is_missing()
    {
        $this
            ->shouldThrow(new \Exception('Location are a mandatory parameter.'))
            ->during('__construct', [new Request()])
        ;
    }
}
