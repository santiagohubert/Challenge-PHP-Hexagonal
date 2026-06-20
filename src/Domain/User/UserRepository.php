<?php

declare(strict_types=1);

namespace Domain\User;

use Domain\Shared\ValueObject\Email;

interface UserRepository
{
    public function findByEmail(Email $email): ?User;

    public function findById(int $id): ?User;
}
