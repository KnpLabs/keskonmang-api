<?php

namespace spec\App\Symfony\Security;

use App\Domain\User;
use App\Google\TokenValidator;
use App\Symfony\Repository\UserRepository;
use App\Symfony\Security\GoogleUserProvider;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GoogleUserProviderSpec extends ObjectBehavior
{
    function let(EntityManagerInterface $orm, TokenValidator $tokenValidator)
    {
        $this->beConstructedWith($orm, $tokenValidator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GoogleUserProvider::class);
    }

    function it_gets_the_user_from_a_token(
        EntityManagerInterface $orm,
        TokenValidator $tokenValidator,
        UserRepository $userRepository,
        User $user
    ) {
        $tokenValidator->verifyToken('token')->willReturn('google-id');

        $orm->getRepository(User::class)->willReturn($userRepository);

        $userRepository->findOneBy(['googleId' => 'google-id'])->willReturn($user);

        $this->getUser('token')->shouldReturn($user);
    }

    function it_create_the_user_from_a_token(
        EntityManagerInterface $orm,
        TokenValidator $tokenValidator,
        UserRepository $userRepository
    ) {
        $tokenValidator->verifyToken('token')->willReturn('google-id');

        $orm->getRepository(User::class)->willReturn($userRepository);
        $orm->persist(Argument::type(User::class))->shouldBeCalled();
        $orm->flush()->shouldBeCalled();

        $userRepository->findOneBy(['googleId' => 'google-id'])->willReturn(null);

        $this->getUser('token')->shouldReturnAnInstanceOf(User::class);
    }

    function it_throw_exception_if_token_is_invalid(TokenValidator $tokenValidator)
    {
        $tokenValidator->verifyToken('token')->willThrow(new \Exception('Invalid token id.'));

        $this->shouldThrow(new \Exception('Invalid token id.'))->during('getUser', ['token']);
    }
}
