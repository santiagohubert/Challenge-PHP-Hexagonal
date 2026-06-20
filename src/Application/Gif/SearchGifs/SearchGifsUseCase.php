<?php

declare(strict_types=1);

namespace Application\Gif\SearchGifs;

use Domain\Gif\GifProvider;
use Domain\Shared\Exception\InvalidArgumentException;

final class SearchGifsUseCase
{
    public function __construct(
        private readonly GifProvider $gifProvider,
    ) {
    }

    public function execute(SearchGifsRequest $request): SearchGifsResponse
    {
        $query = trim($request->query);

        if ($query === '') {
            throw new InvalidArgumentException('QUERY parameter is required.');
        }

        if ($request->limit !== null && $request->limit < 1) {
            throw new InvalidArgumentException('LIMIT must be a positive number.');
        }

        if ($request->offset !== null && $request->offset < 0) {
            throw new InvalidArgumentException('OFFSET must be zero or greater.');
        }

        $gifs = $this->gifProvider->search($query, $request->limit, $request->offset);

        return new SearchGifsResponse($gifs);
    }
}
