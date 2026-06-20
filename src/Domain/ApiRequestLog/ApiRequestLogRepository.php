<?php

declare(strict_types=1);

namespace Domain\ApiRequestLog;

interface ApiRequestLogRepository
{
    public function save(ApiRequestLog $log): void;
}
