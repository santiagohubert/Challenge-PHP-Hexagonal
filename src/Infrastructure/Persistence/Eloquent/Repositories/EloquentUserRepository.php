<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Eloquent\Repositories;

use Domain\Shared\ValueObject\Email;
use Domain\User\User;
use Domain\User\UserRepository;
use Infrastructure\Persistence\Eloquent\Models\UserModel;

final class EloquentUserRepository implements UserRepository
{
    public function findByEmail(Email $email): ?User
    {
        $model = UserModel::query()->where('email', $email->value())->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function findById(int $id): ?User
    {
        $model = UserModel::query()->find($id);

        return $model ? $this->toDomain($model) : null;
    }

    private function toDomain(UserModel $model): User
    {
        return new User(
            id: (int) $model->id,
            name: (string) $model->name,
            email: Email::fromString((string) $model->email),
            passwordHash: (string) $model->password,
        );
    }
}
