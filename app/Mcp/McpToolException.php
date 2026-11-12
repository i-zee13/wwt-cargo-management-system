<?php

namespace App\Mcp;

use RuntimeException;

class McpToolException extends RuntimeException
{
    public function __construct(public string $errorCode, string $message)
    {
        parent::__construct($message);
    }
}
