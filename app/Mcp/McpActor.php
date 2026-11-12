<?php

namespace App\Mcp;

class McpActor
{
    public function __construct(
        public int $tokenId,
        public string $type,
        public int $id,
        public string $workspaceId,
        public mixed $designationId = null,
        public bool $super = false,
    ) {}

    public function isClient(): bool
    {
        return $this->type === 'client';
    }
}
