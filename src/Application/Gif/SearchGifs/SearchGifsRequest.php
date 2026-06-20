<?php

declare(strict_types=1);

namespace Application\Gif\SearchGifs;

final readonly class SearchGifsRequest
{
    public function __construct(
        public string $query,
        public ?int $limit,
        public ?int $offset,
    ) {
    }
}
