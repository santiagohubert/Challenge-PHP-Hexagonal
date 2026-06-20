<?php

namespace Tests\Unit\Application\Auth;

use Application\Auth\AuthTokenService;
use Application\Auth\Login\LoginRequest;
use Application\Auth\Login\LoginResponse;
use Application\Auth\Login\LoginUseCase;
use PHPUnit\Framework\TestCase;

final class LoginUseCaseTest extends TestCase
{
    public function test_it_delegates_authentication_to_auth_token_service(): void
    {
        $request = new LoginRequest('demo@challenge.test', 'password123');
        $expectedResponse = new LoginResponse('token-value', 'Bearer', 1800, 1);

        $authTokenService = $this->createMock(AuthTokenService::class);
        $authTokenService->expects($this->once())
            ->method('authenticate')
            ->with($request)
            ->willReturn($expectedResponse);

        $useCase = new LoginUseCase($authTokenService);

        $this->assertSame($expectedResponse, $useCase->execute($request));
    }
}
