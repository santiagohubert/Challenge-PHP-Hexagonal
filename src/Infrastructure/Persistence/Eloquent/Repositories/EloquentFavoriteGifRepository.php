<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Eloquent\Repositories;

use Domain\FavoriteGif\FavoriteGif;
use Domain\FavoriteGif\FavoriteGifRepository;
use Infrastructure\Persistence\Eloquent\Models\FavoriteGifModel;

final class EloquentFavoriteGifRepository implements FavoriteGifRepository
{
    public function save(FavoriteGif $favoriteGif): FavoriteGif
    {
        $model = FavoriteGifModel::query()->updateOrCreate(
            [
                'user_id' => $favoriteGif->userId(),
                'gif_id' => $favoriteGif->gifId(),
            ],
            [
                'alias' => $favoriteGif->alias(),
                'title' => $favoriteGif->title(),
                'url' => $favoriteGif->url(),
            ],
        );

        return $this->toDomain($model);
    }

    public function existsForUser(int $userId, string $gifId): bool
    {
        return FavoriteGifModel::query()
            ->where('user_id', $userId)
            ->where('gif_id', $gifId)
            ->exists();
    }

    private function toDomain(FavoriteGifModel $model): FavoriteGif
    {
        return new FavoriteGif(
            id: (int) $model->id,
            userId: (int) $model->user_id,
            gifId: (string) $model->gif_id,
            alias: (string) $model->alias,
            title: $model->title !== null ? (string) $model->title : null,
            url: $model->url !== null ? (string) $model->url : null,
        );
    }
}
