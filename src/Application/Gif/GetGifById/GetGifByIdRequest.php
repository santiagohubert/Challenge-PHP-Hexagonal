<?php

declare(strict_types=1);

namespace Application\Gif\GetGifById;

final readonly class GetGifByIdRequest
{
    public function __construct(public string $id)
    {
    }
}
