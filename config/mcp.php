<?php

return [

    /*
    | Master kill switch. Default false so production stays closed until
    | MCP_ENABLED=true is set intentionally. The settings page can still
    | turn the runtime UI flag off without changing .env.
    */
    'enabled' => filter_var(env('MCP_ENABLED', false), FILTER_VALIDATE_BOOLEAN),

    'rate_limit_per_minute' => (int) env('WWT_MCP_RATE_LIMIT', 60),

    'timeout_seconds' => (int) env('WWT_MCP_TIMEOUT', 15),

    'workspace_header' => 'X-Wwt-Workspace',

    'protocol_versions' => ['2025-03-26', '2025-06-18'],

    'server' => [
        'name' => 'wwt-cargo',
        'version' => '1.0.0',
    ],

];
