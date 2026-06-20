<?php

declare(strict_types=1);

namespace Application\Shared;

use Domain\ApiRequestLog\ApiRequestLog;
use Domain\ApiRequestLog\ApiRequestLogRepository;

final class ApiRequestLogger
{
    public function __construct(
        private readonly ApiRequestLogRepository $apiRequestLogRepository,
    ) {
    }

    /**
     * @param array<string, mixed>|null $requestBody
     * @param array<string, mixed>|string|null $responseBody
     */
    public function log(
        string $serviceName,
        ?int $userId,
        ?array $requestBody,
        int $httpStatusCode,
        array|string|null $responseBody,
        string $ipAddress,
    ): void {
        $this->apiRequestLogRepository->save(new ApiRequestLog(
            id: null,
            userId: $userId,
            serviceName: $serviceName,
            requestBody: $requestBody,
            httpStatusCode: $httpStatusCode,
            responseBody: $responseBody,
            ipAddress: $ipAddress,
        ));
    }
}
