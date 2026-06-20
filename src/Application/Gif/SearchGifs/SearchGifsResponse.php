<?php

declare(strict_types=1);

namespace Application\Gif\SearchGifs;

use Domain\Gif\Gif;

final readonly class SearchGifsResponse
{
    /**
     * @param Gif[] $gifs
     */
    public function __construct(public array $gifs)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'data' => array_map(static fn (Gif $gif): array => $gif->toArray(), $this->gifs),
        ];
    }
}
