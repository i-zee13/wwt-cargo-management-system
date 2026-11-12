<?php

namespace Tests\Unit;

use App\Mcp\SecretRedactor;
use Tests\TestCase;

class SecretRedactorTest extends TestCase
{
    public function test_it_removes_bearer_tokens(): void
    {
        $clean = SecretRedactor::clean('Authorization Bearer abc.secret failed abc.secret', 'abc.secret');

        $this->assertSame('Authorization Bearer [REDACTED] failed [REDACTED]', $clean);
        $this->assertStringNotContainsString('abc.secret', $clean);
    }
}
