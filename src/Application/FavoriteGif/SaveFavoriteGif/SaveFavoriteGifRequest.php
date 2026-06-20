<?php

declare(strict_types=1);

namespace Application\FavoriteGif\SaveFavoriteGif;

final readonly class SaveFavoriteGifRequest
{
    public function __construct(
        public int $userId,
        public string $gifId,
        public string $alias,
    ) {
    }
}
