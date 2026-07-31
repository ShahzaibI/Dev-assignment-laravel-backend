<?php

namespace App\Console\Commands;

use App\Models\Page;
use Illuminate\Console\Command;

class PublishScheduledPages extends Command
{
    protected $signature   = 'pages:publish-scheduled';
    protected $description = 'Publish pages whose publish_date has arrived';

    public function handle(): int
    {
        $count = Page::where('status', 'scheduled')
            ->where('publish_date', '<=', now())
            ->update(['status' => 'published']);

        $this->info("Published {$count} scheduled page(s).");

        return self::SUCCESS;
    }
}
