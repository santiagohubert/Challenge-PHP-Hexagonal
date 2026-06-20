<?php

declare(strict_types=1);

namespace Application\Auth\Login;

final readonly class LoginRequest
{
    public function __construct(
        public string $email,
        public string $password,
    ) {
    }
}
