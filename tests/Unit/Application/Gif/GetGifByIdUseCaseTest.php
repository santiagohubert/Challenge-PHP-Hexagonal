<?php

namespace Tests\Unit\Application\Gif;

use Application\Gif\GetGifById\GetGifByIdRequest;
use Application\Gif\GetGifById\GetGifByIdUseCase;
use Domain\Gif\Gif;
use Domain\Gif\GifProvider;
use Domain\Shared\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class GetGifByIdUseCaseTest extends TestCase
{
    public function test_it_requires_id(): void
    {
        $provider = $this->createMock(GifProvider::class);
        $provider->expects($this->never())->method('findById');

        $useCase = new GetGifByIdUseCase($provider);

        $this->expectException(InvalidArgumentException::class);

        $useCase->execute(new GetGifByIdRequest(''));
    }

    public function test_it_returns_gif_by_id(): void
    {
        $gif = new Gif('abc123', 'Title', 'https://gif.test/1.gif', 'https://gif.test/1-preview.gif', []);

        $provider = $this->createMock(GifProvider::class);
        $provider->expects($this->once())->method('findById')->with('abc123')->willReturn($gif);

        $useCase = new GetGifByIdUseCase($provider);
        $response = $useCase->execute(new GetGifByIdRequest('abc123'));

        $this->assertSame('abc123', $response->gif->id());
    }
}
