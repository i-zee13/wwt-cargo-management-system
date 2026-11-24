<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class McpAccessToken extends Model
{
    protected $table = 'mcp_access_tokens';

    protected $guarded = [];

    protected $hidden = [
        'token_hash',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];
}
