<?php

namespace App\Console\Commands;

use App\Models\ClientPortalNotifyBatch;
use App\Models\ClientPortalNotifyItem;
use App\Models\ClientsModel;
use App\Services\ClientPortalNotifyMailer;
use Illuminate\Console\Command;

class SendClientPortalNotifyCommand extends Command
{
    protected $signature = 'clients:send-portal-notify {--limit=25 : Max emails to send this run}';

    protected $description = 'Send queued new-portal notification emails to clients (chunked for cPanel cron)';

    public function handle(ClientPortalNotifyMailer $mailer): int
    {
        $limit = max(1, (int) $this->option('limit'));

        $items = ClientPortalNotifyItem::query()
            ->where('status', 'pending')
            ->orderBy('id')
            ->limit($limit)
            ->get();

        if ($items->isEmpty()) {
            $this->info('No pending portal notify emails.');

            return self::SUCCESS;
        }

        $batchIds = [];

        foreach ($items as $item) {
            $batchIds[$item->batch_id] = true;
            $batch = ClientPortalNotifyBatch::find($item->batch_id);
            if ($batch && $batch->status === 'pending') {
                $batch->update(['status' => 'processing']);
            }

            $client = ClientsModel::find($item->client_id);
            if (! $client || ! filter_var($client->email, FILTER_VALIDATE_EMAIL)) {
                $item->update([
                    'status' => 'failed',
                    'error' => 'Client missing or invalid email',
                ]);
                if ($batch) {
                    $batch->increment('failed');
                }
                continue;
            }

            try {
                $mailer->send($client, $batch->subject, $batch->body_template);
                $item->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                    'error' => null,
                ]);
                if ($batch) {
                    $batch->increment('sent');
                }
                $this->info("Sent to {$client->email}");
            } catch (\Throwable $e) {
                $item->update([
                    'status' => 'failed',
                    'error' => $e->getMessage(),
                ]);
                if ($batch) {
                    $batch->increment('failed');
                }
                $this->error("Failed {$client->email}: ".$e->getMessage());
            }
        }

        foreach (array_keys($batchIds) as $batchId) {
            $batch = ClientPortalNotifyBatch::find($batchId);
            if (! $batch) {
                continue;
            }
            $pending = $batch->items()->where('status', 'pending')->count();
            if ($pending === 0) {
                $batch->update(['status' => 'completed']);
            }
        }

        return self::SUCCESS;
    }
}
