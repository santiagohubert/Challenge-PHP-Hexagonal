<?php

namespace Tests\Unit\Application\FavoriteGif;

use Application\FavoriteGif\SaveFavoriteGif\SaveFavoriteGifRequest;
use Application\FavoriteGif\SaveFavoriteGif\SaveFavoriteGifUseCase;
use Domain\FavoriteGif\FavoriteGif;
use Domain\FavoriteGif\FavoriteGifRepository;
use Domain\Gif\Gif;
use Domain\Gif\GifProvider;
use Domain\Shared\Exception\NotFoundException;
use Domain\Shared\ValueObject\Email;
use Domain\User\User;
use Domain\User\UserRepository;
use PHPUnit\Framework\TestCase;

final class SaveFavoriteGifUseCaseTest extends TestCase
{
    public function test_it_throws_when_user_does_not_exist(): void
    {
        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->method('findById')->willReturn(null);

        $useCase = new SaveFavoriteGifUseCase(
            $userRepository,
            $this->createMock(FavoriteGifRepository::class),
            $this->createMock(GifProvider::class),
        );

        $this->expectException(NotFoundException::class);

        $useCase->execute(new SaveFavoriteGifRequest(99, 'abc123', 'My alias'));
    }

    public function test_it_saves_favorite_gif(): void
    {
        $user = new User(1, 'Demo', Email::fromString('demo@challenge.test'), 'hash');
        $gif = new Gif('abc123', 'Dancing', 'https://gif.test/d.gif', 'https://gif.test/d-preview.gif', []);
        $savedFavorite = new FavoriteGif(10, 1, 'abc123', 'My alias', 'Dancing', 'https://gif.test/d.gif');

        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->method('findById')->willReturn($user);

        $gifProvider = $this->createMock(GifProvider::class);
        $gifProvider->method('findById')->with('abc123')->willReturn($gif);

        $favoriteRepository = $this->createMock(FavoriteGifRepository::class);
        $favoriteRepository->expects($this->once())->method('save')->willReturn($savedFavorite);

        $useCase = new SaveFavoriteGifUseCase($userRepository, $favoriteRepository, $gifProvider);
        $response = $useCase->execute(new SaveFavoriteGifRequest(1, 'abc123', 'My alias'));

        $this->assertSame(10, $response->favoriteGif->id());
        $this->assertSame('My alias', $response->toArray()['data']['alias']);
    }
}
