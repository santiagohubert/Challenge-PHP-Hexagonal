<?php

declare(strict_types=1);

namespace Infrastructure\Http\Exceptions;

use Domain\Shared\Exception\DomainException;
use Domain\Shared\Exception\InvalidArgumentException;
use Domain\Shared\Exception\NotFoundException;
use Domain\Shared\Exception\UnauthorizedException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infrastructure\Shared\Exception\ExternalServiceException;

final class ExceptionHandler
{
    public static function register(Exceptions $exceptions): void
    {
        $exceptions->render(function (AuthenticationException $exception, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                ], JsonResponse::HTTP_UNAUTHORIZED);
            }

            return null;
        });

        $exceptions->render(function (UnauthorizedException $exception, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $exception->getMessage(),
                ], JsonResponse::HTTP_UNAUTHORIZED);
            }

            return null;
        });

        $exceptions->render(function (InvalidArgumentException $exception, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $exception->getMessage(),
                ], JsonResponse::HTTP_BAD_REQUEST);
            }

            return null;
        });

        $exceptions->render(function (NotFoundException $exception, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $exception->getMessage(),
                ], JsonResponse::HTTP_NOT_FOUND);
            }

            return null;
        });

        $exceptions->render(function (ExternalServiceException $exception, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $exception->getMessage(),
                ], JsonResponse::HTTP_BAD_GATEWAY);
            }

            return null;
        });

        $exceptions->render(function (DomainException $exception, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $exception->getMessage(),
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }

            return null;
        });
    }
}
