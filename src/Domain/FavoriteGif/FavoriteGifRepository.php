<?php

declare(strict_types=1);

namespace Domain\FavoriteGif;

interface FavoriteGifRepository
{
    public function save(FavoriteGif $favoriteGif): FavoriteGif;

    public function existsForUser(int $userId, string $gifId): bool;
}
