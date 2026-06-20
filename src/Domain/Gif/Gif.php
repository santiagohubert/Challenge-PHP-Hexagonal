<?php

declare(strict_types=1);

namespace Domain\Gif;

final readonly class Gif
{
    /**
     * @param array<string, mixed> $rawData
     */
    public function __construct(
        private string $id,
        private string $title,
        private string $url,
        private string $previewUrl,
        private array $rawData,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function previewUrl(): string
    {
        return $this->previewUrl;
    }

    /**
     * @return array<string, mixed>
     */
    public function rawData(): array
    {
        return $this->rawData;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'url' => $this->url,
            'preview_url' => $this->previewUrl,
        ];
    }
}
