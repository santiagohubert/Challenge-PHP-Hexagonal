<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Eloquent\Repositories;

use Domain\ApiRequestLog\ApiRequestLog;
use Domain\ApiRequestLog\ApiRequestLogRepository;
use Infrastructure\Persistence\Eloquent\Models\ApiRequestLogModel;

final class EloquentApiRequestLogRepository implements ApiRequestLogRepository
{
    public function save(ApiRequestLog $log): void
    {
        ApiRequestLogModel::query()->create([
            'user_id' => $log->userId(),
            'service_name' => $log->serviceName(),
            'request_body' => $log->requestBody(),
            'http_status_code' => $log->httpStatusCode(),
            'response_body' => $this->normalizeResponseBody($log->responseBody()),
            'ip_address' => $log->ipAddress(),
        ]);
    }

    private function normalizeResponseBody(array|string|null $responseBody): ?array
    {
        if ($responseBody === null) {
            return null;
        }

        if (is_array($responseBody)) {
            return $responseBody;
        }

        $decoded = json_decode($responseBody, true);

        return is_array($decoded) ? $decoded : ['message' => $responseBody];
    }
}
