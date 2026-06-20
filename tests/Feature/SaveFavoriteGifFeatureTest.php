<?php

namespace Tests\Feature;

use Domain\Gif\Gif;
use Domain\Gif\GifProvider;
use Infrastructure\Persistence\Eloquent\Models\UserModel;
use Laravel\Passport\Passport;

final class SaveFavoriteGifFeatureTest extends FeatureTestCase
{
    public function test_user_id_must_match_authenticated_user(): void
    {
        $user = UserModel::query()->where('email', 'demo@challenge.test')->firstOrFail();
        Passport::actingAs($user);

        $response = $this->postJson('/api/favorites', [
            'gif_id' => 'abc123',
            'alias' => 'My favorite',
            'user_id' => 999,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['user_id']);
    }

    public function test_authenticated_user_can_save_favorite_gif(): void
    {
        $user = UserModel::query()->where('email', 'demo@challenge.test')->firstOrFail();
        Passport::actingAs($user);

        $gifProvider = $this->createMock(GifProvider::class);
        $gifProvider->method('findById')
            ->with('abc123')
            ->willReturn(new Gif('abc123', 'Dancing', 'https://gif.test/d.gif', 'https://gif.test/p.gif', []));

        $this->app->instance(GifProvider::class, $gifProvider);

        $response = $this->postJson('/api/favorites', [
            'gif_id' => 'abc123',
            'alias' => 'My favorite',
            'user_id' => $user->id,
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.alias', 'My favorite')
            ->assertJsonPath('data.gif_id', 'abc123');
    }
}
