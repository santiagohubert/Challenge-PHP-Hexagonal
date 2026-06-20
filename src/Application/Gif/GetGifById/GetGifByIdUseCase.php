<?php

declare(strict_types=1);

namespace Application\Gif\GetGifById;

use Domain\Gif\GifProvider;
use Domain\Shared\Exception\InvalidArgumentException;

final class GetGifByIdUseCase
{
    public function __construct(
        private readonly GifProvider $gifProvider,
    ) {
    }

    public function execute(GetGifByIdRequest $request): GetGifByIdResponse
    {
        $id = trim($request->id);

        if ($id === '') {
            throw new InvalidArgumentException('ID parameter is required.');
        }

        return new GetGifByIdResponse($this->gifProvider->findById($id));
    }
}
