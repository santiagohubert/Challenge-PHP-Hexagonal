<?php

declare(strict_types=1);

namespace Application\FavoriteGif\SaveFavoriteGif;

use Domain\FavoriteGif\FavoriteGif;
use Domain\FavoriteGif\FavoriteGifRepository;
use Domain\Gif\GifProvider;
use Domain\Shared\Exception\InvalidArgumentException;
use Domain\Shared\Exception\NotFoundException;
use Domain\User\UserRepository;

final class SaveFavoriteGifUseCase
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly FavoriteGifRepository $favoriteGifRepository,
        private readonly GifProvider $gifProvider,
    ) {
    }

    public function execute(SaveFavoriteGifRequest $request): SaveFavoriteGifResponse
    {
        $gifId = trim($request->gifId);
        $alias = trim($request->alias);

        if ($gifId === '') {
            throw new InvalidArgumentException('GIF_ID is required.');
        }

        if ($alias === '') {
            throw new InvalidArgumentException('ALIAS is required.');
        }

        if ($request->userId < 1) {
            throw new InvalidArgumentException('USER_ID is required.');
        }

        $user = $this->userRepository->findById($request->userId);

        if ($user === null) {
            throw new NotFoundException('User not found.');
        }

        $gif = $this->gifProvider->findById($gifId);

        $favorite = new FavoriteGif(
            id: 0,
            userId: $user->id(),
            gifId: $gif->id(),
            alias: $alias,
            title: $gif->title(),
            url: $gif->url(),
        );

        $savedFavorite = $this->favoriteGifRepository->save($favorite);

        return new SaveFavoriteGifResponse($savedFavorite);
    }
}
