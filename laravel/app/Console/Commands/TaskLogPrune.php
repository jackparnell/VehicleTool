<?php

namespace App\Console\Commands;

use App\Ryder\TaskLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TaskLogPrune extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'taskLog:prune';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune old and unneeded rows from the taskLog table.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ob_start();

        try {

            $taskLog = new TaskLog();
            $taskLog->start(get_class($this), 300);

            DB::table('taskLog')->where('end', '<', date('Y-m-d H:i:s', strtotime('-7 days')))->delete();

            $taskLog->end();

        }
        catch (\Exception $e) {

            if (isset($taskLog)) {
                $taskLog->exception($e);
            }

        }

        $output = ob_get_contents();
        ob_end_clean();

        if (strtoupper(PHP_SAPI) == 'CLI') {
            echo $output;
        }

        return $output;

    }
}
