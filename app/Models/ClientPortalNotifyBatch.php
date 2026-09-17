<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientPortalNotifyBatch extends Model
{
    protected $table = 'client_portal_notify_batches';

    protected $guarded = [];

    public function items(): HasMany
    {
        return $this->hasMany(ClientPortalNotifyItem::class, 'batch_id');
    }
}
