<?php

declare(strict_types=1);

namespace Infrastructure\Http\Controllers;

use Application\Shared\ApiRequestLogger;
use Domain\Shared\Exception\DomainException;
use Domain\Shared\Exception\InvalidArgumentException;
use Domain\Shared\Exception\NotFoundException;
use Domain\Shared\Exception\UnauthorizedException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Infrastructure\Shared\Exception\ExternalServiceException;

abstract class BaseApiController extends Controller
{
    public function __construct(
        protected readonly ApiRequestLogger $apiRequestLogger,
    ) {
    }

    /**
     * @param array<string, mixed>|null $requestBody
     */
    protected function logRequest(
        Request $request,
        string $serviceName,
        ?int $userId,
        ?array $requestBody,
        JsonResponse $response,
    ): void {
        $this->apiRequestLogger->log(
            serviceName: $serviceName,
            userId: $userId,
            requestBody: $requestBody,
            httpStatusCode: $response->getStatusCode(),
            responseBody: $response->getData(true),
            ipAddress: $request->ip() ?? '0.0.0.0',
        );
    }

    protected function executeSafely(callable $callback): JsonResponse
    {
        try {
            return $callback();
        } catch (InvalidArgumentException $exception) {
            return response()->json(['message' => $exception->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        } catch (UnauthorizedException $exception) {
            return response()->json(['message' => $exception->getMessage()], JsonResponse::HTTP_UNAUTHORIZED);
        } catch (NotFoundException $exception) {
            return response()->json(['message' => $exception->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        } catch (ExternalServiceException $exception) {
            return response()->json(['message' => $exception->getMessage()], JsonResponse::HTTP_BAD_GATEWAY);
        } catch (DomainException $exception) {
            return response()->json(['message' => $exception->getMessage()], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    protected function authenticatedUserId(Request $request): ?int
    {
        $user = $request->user();

        return $user !== null ? (int) $user->id : null;
    }
}
