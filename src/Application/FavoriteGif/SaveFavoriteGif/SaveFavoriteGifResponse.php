<?php

declare(strict_types=1);

namespace Application\FavoriteGif\SaveFavoriteGif;

use Domain\FavoriteGif\FavoriteGif;

final readonly class SaveFavoriteGifResponse
{
    public function __construct(public FavoriteGif $favoriteGif)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'data' => $this->favoriteGif->toArray(),
        ];
    }
}
