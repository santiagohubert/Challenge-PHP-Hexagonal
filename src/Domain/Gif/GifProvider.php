<?php

declare(strict_types=1);

namespace Domain\Gif;

interface GifProvider
{
    /**
     * @return Gif[]
     */
    public function search(string $query, ?int $limit, ?int $offset): array;

    public function findById(string $id): Gif;
}
