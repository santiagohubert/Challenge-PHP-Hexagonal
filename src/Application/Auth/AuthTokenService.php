<?php

declare(strict_types=1);

namespace Application\Auth;

use Application\Auth\Login\LoginRequest;
use Application\Auth\Login\LoginResponse;

interface AuthTokenService
{
    public function authenticate(LoginRequest $request): LoginResponse;
}
