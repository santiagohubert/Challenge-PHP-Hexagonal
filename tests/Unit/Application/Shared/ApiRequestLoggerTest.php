<?php

namespace Tests\Unit\Application\Shared;

use Application\Shared\ApiRequestLogger;
use Domain\ApiRequestLog\ApiRequestLog;
use Domain\ApiRequestLog\ApiRequestLogRepository;
use PHPUnit\Framework\TestCase;

final class ApiRequestLoggerTest extends TestCase
{
    public function test_it_persists_request_audit_log(): void
    {
        $repository = $this->createMock(ApiRequestLogRepository::class);
        $repository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (ApiRequestLog $log): bool {
                return $log->userId() === 1
                    && $log->serviceName() === 'search_gifs'
                    && $log->httpStatusCode() === 200
                    && $log->ipAddress() === '127.0.0.1';
            }));

        $logger = new ApiRequestLogger($repository);

        $logger->log(
            serviceName: 'search_gifs',
            userId: 1,
            requestBody: ['query' => 'cat'],
            httpStatusCode: 200,
            responseBody: ['data' => []],
            ipAddress: '127.0.0.1',
        );
    }
}
