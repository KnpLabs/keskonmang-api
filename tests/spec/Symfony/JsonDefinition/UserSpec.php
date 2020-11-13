<?php

namespace spec\App\Symfony\JsonDefinition;

use App\Domain\User as UserEntity;
use App\Symfony\JsonDefinition\User;
use PhpSpec\ObjectBehavior;

class UserSpec extends ObjectBehavior
{
    function let(UserEntity $user)
    {
        $user->getId()->willReturn(1);
        $user->getGoogleId()->willReturn('2');

        $this->beConstructedWith($user);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(User::class);
    }

    function it_has_an_id()
    {
        $this->id->shouldBe(1);
    }

    function it_has_a_google_id()
    {
        $this->googleId->shouldBe('2');
    }
}
