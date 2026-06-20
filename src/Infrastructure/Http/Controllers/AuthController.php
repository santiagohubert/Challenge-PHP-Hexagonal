<?php

declare(strict_types=1);

namespace Infrastructure\Http\Controllers;

use Application\Auth\Login\LoginRequest as LoginUseCaseRequest;
use Application\Auth\Login\LoginResponse;
use Application\Auth\Login\LoginUseCase;
use Application\Shared\ApiRequestLogger;
use Illuminate\Http\JsonResponse;
use Infrastructure\Http\Requests\LoginRequest;

final class AuthController extends BaseApiController
{
    public function __construct(
        ApiRequestLogger $apiRequestLogger,
        private readonly LoginUseCase $loginUseCase,
    ) {
        parent::__construct($apiRequestLogger);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $requestBody = [
            'email' => $validated['email'],
            'password' => '***',
        ];

        $loginResponse = null;

        $jsonResponse = $this->executeSafely(function () use ($validated, &$loginResponse): JsonResponse {
            $loginResponse = $this->loginUseCase->execute(new LoginUseCaseRequest(
                email: $validated['email'],
                password: $validated['password'],
            ));

            return response()->json($loginResponse->toArray(), JsonResponse::HTTP_OK);
        });

        $this->logRequest(
            $request,
            'login',
            $loginResponse instanceof LoginResponse ? $loginResponse->userId : null,
            $requestBody,
            $jsonResponse,
        );

        return $jsonResponse;
    }
}
