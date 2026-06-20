<?php

declare(strict_types=1);

namespace Domain\FavoriteGif;

final class FavoriteGif
{
    public function __construct(
        private readonly int $id,
        private readonly int $userId,
        private readonly string $gifId,
        private readonly string $alias,
        private readonly ?string $title,
        private readonly ?string $url,
    ) {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function userId(): int
    {
        return $this->userId;
    }

    public function gifId(): string
    {
        return $this->gifId;
    }

    public function alias(): string
    {
        return $this->alias;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function url(): ?string
    {
        return $this->url;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'gif_id' => $this->gifId,
            'alias' => $this->alias,
            'title' => $this->title,
            'url' => $this->url,
        ];
    }
}
