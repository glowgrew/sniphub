<?php

namespace App\Console\Commands;

use App\Models\Snippet;
use Illuminate\Console\Command;

class DeleteExpiredSnippets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-expired-snippets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Snippet::query()->where('expiration_time', '<', now())->delete();
    }
}
