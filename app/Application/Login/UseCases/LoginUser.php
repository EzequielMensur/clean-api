<?php

namespace App\Application\Login\UseCases;

use App\Application\Login\DTOs\LoginRequestDto;
use App\Application\Login\DTOs\LoginResponseDto;
use App\Domain\Services\TokenService;
use App\Domain\User\Repositories\UserRepository;

final class LoginUser
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly TokenService $tokens
    ) {}

    public function __invoke(LoginRequestDto $dto): ?LoginResponseDto
    {
        $user = $this->users->findByEmail($dto->email);

        $token = $this->tokens->fromUser($user);

        return new LoginResponseDto(
            token: $token,
            tokenType: 'Bearer',
            expiresIn: config('jwt.ttl') * 60
        );
    }
}
