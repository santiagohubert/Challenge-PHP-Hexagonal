<?php

namespace Tests\Unit\Application\Gif;

use Application\Gif\SearchGifs\SearchGifsRequest;
use Application\Gif\SearchGifs\SearchGifsUseCase;
use Domain\Gif\Gif;
use Domain\Gif\GifProvider;
use Domain\Shared\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class SearchGifsUseCaseTest extends TestCase
{
    public function test_it_requires_query_parameter(): void
    {
        $provider = $this->createMock(GifProvider::class);
        $provider->expects($this->never())->method('search');

        $useCase = new SearchGifsUseCase($provider);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('QUERY parameter is required.');

        $useCase->execute(new SearchGifsRequest('', null, null));
    }

    public function test_it_returns_search_results(): void
    {
        $gif = new Gif('1', 'Funny cat', 'https://gif.test/1.gif', 'https://gif.test/1-preview.gif', []);

        $provider = $this->createMock(GifProvider::class);
        $provider->expects($this->once())
            ->method('search')
            ->with('cat', 10, 0)
            ->willReturn([$gif]);

        $useCase = new SearchGifsUseCase($provider);
        $response = $useCase->execute(new SearchGifsRequest('cat', 10, 0));

        $this->assertCount(1, $response->gifs);
        $this->assertSame('1', $response->gifs[0]->id());
        $this->assertSame('Funny cat', $response->toArray()['data'][0]['title']);
    }

    public function test_it_rejects_non_positive_limit(): void
    {
        $provider = $this->createMock(GifProvider::class);
        $provider->expects($this->never())->method('search');

        $useCase = new SearchGifsUseCase($provider);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('LIMIT must be a positive number.');

        $useCase->execute(new SearchGifsRequest('cat', 0, null));
    }

    public function test_it_rejects_negative_offset(): void
    {
        $provider = $this->createMock(GifProvider::class);
        $provider->expects($this->never())->method('search');

        $useCase = new SearchGifsUseCase($provider);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('OFFSET must be zero or greater.');

        $useCase->execute(new SearchGifsRequest('cat', null, -1));
    }
}
