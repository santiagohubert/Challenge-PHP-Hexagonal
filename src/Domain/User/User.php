<?php

declare(strict_types=1);

namespace Domain\User;

use Domain\Shared\ValueObject\Email;

final class User
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly Email $email,
        private readonly string $passwordHash,
    ) {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function passwordHash(): string
    {
        return $this->passwordHash;
    }
}
