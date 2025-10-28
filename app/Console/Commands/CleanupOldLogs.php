<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Log;
use Carbon\Carbon;

class CleanupOldLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:cleanup {--days=120 : Number of days to keep logs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old logs older than specified days (default: 120 days)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');

        $this->info("Cleaning up logs older than {$days} days...");

        $cutoffDate = Carbon::now()->subDays($days);

        $count = Log::where('created_at', '<', $cutoffDate)->delete();

        $this->info("Deleted {$count} old log entries.");

        return Command::SUCCESS;
    }
}
