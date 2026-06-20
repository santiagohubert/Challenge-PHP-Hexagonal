<?php

declare(strict_types=1);

namespace Domain\ApiRequestLog;

final class ApiRequestLog
{
    /**
     * @param array<string, mixed>|null $requestBody
     * @param array<string, mixed>|string|null $responseBody
     */
    public function __construct(
        private readonly ?int $id,
        private readonly ?int $userId,
        private readonly string $serviceName,
        private readonly ?array $requestBody,
        private readonly int $httpStatusCode,
        private readonly array|string|null $responseBody,
        private readonly string $ipAddress,
    ) {
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function userId(): ?int
    {
        return $this->userId;
    }

    public function serviceName(): string
    {
        return $this->serviceName;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function requestBody(): ?array
    {
        return $this->requestBody;
    }

    public function httpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    public function responseBody(): array|string|null
    {
        return $this->responseBody;
    }

    public function ipAddress(): string
    {
        return $this->ipAddress;
    }
}
