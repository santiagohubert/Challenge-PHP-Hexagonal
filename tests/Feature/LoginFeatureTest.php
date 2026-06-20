<?php

namespace Tests\Feature;

use Infrastructure\Persistence\Eloquent\Models\ApiRequestLogModel;
use Infrastructure\Persistence\Eloquent\Models\UserModel;

final class LoginFeatureTest extends FeatureTestCase
{
    public function test_login_returns_oauth_token_with_thirty_minute_expiration(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'demo@challenge.test',
            'password' => 'password123',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in'])
            ->assertJson([
                'token_type' => 'Bearer',
                'expires_in' => 1800,
            ]);
    }

    public function test_login_with_invalid_credentials_returns_unauthorized(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'demo@challenge.test',
            'password' => 'wrong-password',
        ]);

        $response->assertUnauthorized()
            ->assertJson(['message' => 'Invalid credentials.']);
    }

    public function test_successful_login_is_audited_with_user_id(): void
    {
        $this->postJson('/api/login', [
            'email' => 'demo@challenge.test',
            'password' => 'password123',
        ])->assertOk();

        $log = ApiRequestLogModel::query()->where('service_name', 'login')->first();
        $user = UserModel::query()->where('email', 'demo@challenge.test')->firstOrFail();

        $this->assertNotNull($log);
        $this->assertSame((int) $user->id, (int) $log->user_id);
        $this->assertSame(200, (int) $log->http_status_code);
        $this->assertSame('***', $log->request_body['password']);
    }
}
