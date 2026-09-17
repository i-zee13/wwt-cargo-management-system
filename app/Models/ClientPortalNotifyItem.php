<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientPortalNotifyItem extends Model
{
    protected $table = 'client_portal_notify_items';

    protected $guarded = [];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(ClientPortalNotifyBatch::class, 'batch_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientsModel::class, 'client_id');
    }
}
