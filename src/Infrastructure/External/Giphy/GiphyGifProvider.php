<?php

declare(strict_types=1);

namespace Infrastructure\External\Giphy;

use Domain\Gif\Gif;
use Domain\Gif\GifProvider;
use Domain\Shared\Exception\NotFoundException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Infrastructure\Shared\Exception\ExternalServiceException;

final class GiphyGifProvider implements GifProvider
{
    public function __construct(
        private readonly ClientInterface $httpClient,
        private readonly string $apiKey,
        private readonly string $baseUrl,
    ) {
    }

    public function search(string $query, ?int $limit, ?int $offset): array
    {
        $queryParams = [
            'api_key' => $this->apiKey,
            'q' => $query,
            'limit' => $limit ?? 25,
            'offset' => $offset ?? 0,
        ];

        $payload = $this->request('GET', '/gifs/search', $queryParams);

        return array_map(
            fn (array $item): Gif => $this->mapGif($item),
            $payload['data'] ?? [],
        );
    }

    public function findById(string $id): Gif
    {
        $payload = $this->request('GET', '/gifs/'.urlencode($id), [
            'api_key' => $this->apiKey,
        ]);

        if (! isset($payload['data']) || ! is_array($payload['data'])) {
            throw new NotFoundException('GIF not found.');
        }

        return $this->mapGif($payload['data']);
    }

    /**
     * @param array<string, mixed> $queryParams
     *
     * @return array<string, mixed>
     */
    private function request(string $method, string $path, array $queryParams): array
    {
        try {
            $response = $this->httpClient->request($method, rtrim($this->baseUrl, '/').$path, [
                'query' => $queryParams,
                'http_errors' => false,
                'timeout' => 10,
            ]);
        } catch (GuzzleException $exception) {
            throw new ExternalServiceException('Unable to connect to GIPHY service.', 0, $exception);
        }

        $statusCode = $response->getStatusCode();
        $body = json_decode((string) $response->getBody(), true);

        if (! is_array($body)) {
            throw new ExternalServiceException('Invalid response from GIPHY service.');
        }

        if ($statusCode === 404) {
            throw new NotFoundException('GIF not found.');
        }

        if ($statusCode >= 400) {
            $message = is_string($body['message'] ?? null) ? $body['message'] : 'GIPHY request failed.';

            throw new ExternalServiceException($message, $statusCode);
        }

        return $body;
    }

    /**
     * @param array<string, mixed> $item
     */
    private function mapGif(array $item): Gif
    {
        $images = is_array($item['images'] ?? null) ? $item['images'] : [];
        $original = is_array($images['original'] ?? null) ? $images['original'] : [];
        $fixedWidth = is_array($images['fixed_width'] ?? null) ? $images['fixed_width'] : [];

        return new Gif(
            id: (string) ($item['id'] ?? ''),
            title: (string) ($item['title'] ?? ''),
            url: (string) ($original['url'] ?? ''),
            previewUrl: (string) ($fixedWidth['url'] ?? $original['url'] ?? ''),
            rawData: $item,
        );
    }
}
