<?php

namespace Tests\Unit\Domain\Shared;

use Domain\Shared\Exception\InvalidArgumentException;
use Domain\Shared\ValueObject\Email;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    public function test_it_creates_valid_email(): void
    {
        $email = Email::fromString('Demo@Challenge.TEST');

        $this->assertSame('demo@challenge.test', $email->value());
    }

    public function test_it_rejects_invalid_email(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Email::fromString('invalid-email');
    }
}
