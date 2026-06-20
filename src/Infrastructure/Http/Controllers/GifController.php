<?php

declare(strict_types=1);

namespace Infrastructure\Http\Controllers;

use Application\FavoriteGif\SaveFavoriteGif\SaveFavoriteGifRequest as SaveFavoriteUseCaseRequest;
use Application\FavoriteGif\SaveFavoriteGif\SaveFavoriteGifUseCase;
use Application\Gif\GetGifById\GetGifByIdRequest as GetGifByIdUseCaseRequest;
use Application\Gif\GetGifById\GetGifByIdUseCase;
use Application\Gif\SearchGifs\SearchGifsRequest as SearchGifsUseCaseRequest;
use Application\Gif\SearchGifs\SearchGifsUseCase;
use Application\Shared\ApiRequestLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infrastructure\Http\Requests\SaveFavoriteGifRequest;
use Infrastructure\Http\Requests\SearchGifsRequest;

final class GifController extends BaseApiController
{
    public function __construct(
        ApiRequestLogger $apiRequestLogger,
        private readonly SearchGifsUseCase $searchGifsUseCase,
        private readonly GetGifByIdUseCase $getGifByIdUseCase,
        private readonly SaveFavoriteGifUseCase $saveFavoriteGifUseCase,
    ) {
        parent::__construct($apiRequestLogger);
    }

    public function search(SearchGifsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $requestBody = [
            'query' => $validated['query'],
            'limit' => $validated['limit'] ?? null,
            'offset' => $validated['offset'] ?? null,
        ];

        $jsonResponse = $this->executeSafely(function () use ($validated): JsonResponse {
            $response = $this->searchGifsUseCase->execute(new SearchGifsUseCaseRequest(
                query: $validated['query'],
                limit: isset($validated['limit']) ? (int) $validated['limit'] : null,
                offset: isset($validated['offset']) ? (int) $validated['offset'] : null,
            ));

            return response()->json($response->toArray(), JsonResponse::HTTP_OK);
        });

        $this->logRequest(
            $request,
            'search_gifs',
            $this->authenticatedUserId($request),
            $requestBody,
            $jsonResponse,
        );

        return $jsonResponse;
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $requestBody = ['id' => $id];

        $jsonResponse = $this->executeSafely(function () use ($id): JsonResponse {
            $response = $this->getGifByIdUseCase->execute(new GetGifByIdUseCaseRequest($id));

            return response()->json($response->toArray(), JsonResponse::HTTP_OK);
        });

        $this->logRequest(
            $request,
            'get_gif_by_id',
            $this->authenticatedUserId($request),
            $requestBody,
            $jsonResponse,
        );

        return $jsonResponse;
    }

    public function saveFavorite(SaveFavoriteGifRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $requestBody = [
            'gif_id' => $validated['gif_id'],
            'alias' => $validated['alias'],
            'user_id' => (int) $validated['user_id'],
        ];

        $jsonResponse = $this->executeSafely(function () use ($validated): JsonResponse {
            $response = $this->saveFavoriteGifUseCase->execute(new SaveFavoriteUseCaseRequest(
                userId: (int) $validated['user_id'],
                gifId: (string) $validated['gif_id'],
                alias: (string) $validated['alias'],
            ));

            return response()->json($response->toArray(), JsonResponse::HTTP_CREATED);
        });

        $this->logRequest(
            $request,
            'save_favorite_gif',
            $this->authenticatedUserId($request),
            $requestBody,
            $jsonResponse,
        );

        return $jsonResponse;
    }
}
