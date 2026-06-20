<?php

declare(strict_types=1);

namespace Infrastructure\Providers;

use Application\Auth\AuthTokenService;
use Domain\ApiRequestLog\ApiRequestLogRepository;
use Domain\FavoriteGif\FavoriteGifRepository;
use Domain\Gif\GifProvider;
use Domain\User\UserRepository;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;
use Infrastructure\Auth\PassportAuthTokenService;
use Infrastructure\External\Giphy\GiphyGifProvider;
use Infrastructure\Persistence\Eloquent\Repositories\EloquentApiRequestLogRepository;
use Infrastructure\Persistence\Eloquent\Repositories\EloquentFavoriteGifRepository;
use Infrastructure\Persistence\Eloquent\Repositories\EloquentUserRepository;

final class HexagonalServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ClientInterface::class, fn (): Client => new Client());

        $this->app->bind(GifProvider::class, function ($app): GiphyGifProvider {
            return new GiphyGifProvider(
                httpClient: $app->make(ClientInterface::class),
                apiKey: (string) config('giphy.api_key'),
                baseUrl: (string) config('giphy.base_url'),
            );
        });

        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
        $this->app->bind(FavoriteGifRepository::class, EloquentFavoriteGifRepository::class);
        $this->app->bind(ApiRequestLogRepository::class, EloquentApiRequestLogRepository::class);

        $this->app->bind(AuthTokenService::class, function ($app): PassportAuthTokenService {
            return new PassportAuthTokenService(
                userRepository: $app->make(UserRepository::class),
                tokenExpirationMinutes: (int) config('passport.token_expiration_minutes', 30),
            );
        });
    }
}
