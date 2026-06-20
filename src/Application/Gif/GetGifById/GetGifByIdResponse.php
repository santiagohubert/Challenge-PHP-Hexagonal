<?php

declare(strict_types=1);

namespace Application\Gif\GetGifById;

use Domain\Gif\Gif;

final readonly class GetGifByIdResponse
{
    public function __construct(public Gif $gif)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'data' => $this->gif->toArray(),
        ];
    }
}
