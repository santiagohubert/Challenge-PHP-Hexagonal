<?php

declare(strict_types=1);

namespace Infrastructure\Auth;

use Application\Auth\AuthTokenService;
use Application\Auth\Login\LoginRequest;
use Application\Auth\Login\LoginResponse;
use Domain\Shared\Exception\UnauthorizedException;
use Domain\Shared\ValueObject\Email;
use Domain\User\UserRepository;
use Illuminate\Support\Facades\Hash;
use Infrastructure\Persistence\Eloquent\Models\UserModel;

final class PassportAuthTokenService implements AuthTokenService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly int $tokenExpirationMinutes,
    ) {
    }

    public function authenticate(LoginRequest $request): LoginResponse
    {
        $email = Email::fromString($request->email);
        $user = $this->userRepository->findByEmail($email);

        if ($user === null || ! Hash::check($request->password, $user->passwordHash())) {
            throw new UnauthorizedException('Invalid credentials.');
        }

        $userModel = UserModel::query()->find($user->id());

        if ($userModel === null) {
            throw new UnauthorizedException('Invalid credentials.');
        }

        $tokenResult = $userModel->createToken('challenge-api-token');
        $tokenResult->token->expires_at = now()->addMinutes($this->tokenExpirationMinutes);
        $tokenResult->token->save();

        return new LoginResponse(
            accessToken: $tokenResult->accessToken,
            tokenType: 'Bearer',
            expiresIn: $this->tokenExpirationMinutes * 60,
            userId: $user->id(),
        );
    }
}
