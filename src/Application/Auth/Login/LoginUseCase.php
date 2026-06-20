<?php

declare(strict_types=1);

namespace Application\Auth\Login;

use Application\Auth\AuthTokenService;

final class LoginUseCase
{
    public function __construct(
        private readonly AuthTokenService $authTokenService,
    ) {
    }

    public function execute(LoginRequest $request): LoginResponse
    {
        return $this->authTokenService->authenticate($request);
    }
}
