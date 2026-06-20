<?php

namespace Tests\Feature;

use Domain\Gif\Gif;
use Domain\Gif\GifProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Infrastructure\Persistence\Eloquent\Models\UserModel;
use Laravel\Passport\Passport;
use Tests\TestCase;

final class ProtectedRoutesFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('passport:install', ['--force' => true]);
        $this->seed();
    }

    public function test_protected_endpoints_require_authentication(): void
    {
        $this->getJson('/api/gifs/search?query=cat')->assertUnauthorized();
        $this->getJson('/api/gifs/abc123')->assertUnauthorized();
        $this->postJson('/api/favorites', [
            'gif_id' => 'abc123',
            'alias' => 'Test',
            'user_id' => 1,
        ])->assertUnauthorized();
    }

    public function test_routes_are_not_double_prefixed(): void
    {
        $this->postJson('/api/api/login', [
            'email' => 'demo@challenge.test',
            'password' => 'password123',
        ])->assertNotFound();
    }

    public function test_authenticated_user_can_access_protected_route(): void
    {
        $user = UserModel::query()->where('email', 'demo@challenge.test')->firstOrFail();
        Passport::actingAs($user);

        $gifProvider = $this->createMock(GifProvider::class);
        $gifProvider->method('search')->willReturn([]);
        $this->app->instance(GifProvider::class, $gifProvider);

        $this->getJson('/api/gifs/search?query=cat')
            ->assertOk()
            ->assertJsonPath('data', []);
    }
}
