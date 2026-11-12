<?php

namespace App\Mcp;

use RuntimeException;

class McpAuthException extends RuntimeException
{
    public function __construct(
        public int $httpStatus,
        public int $rpcCode,
        public string $errorCode,
        string $message,
    ) {
        parent::__construct($message);
    }
}
