<?php

namespace App\Console\Commands;

use Exception;
use App\Models\Board;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Boards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'titan:boards';

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
        try {

            Board::query()
                ->get()
                ->each(function (Board $board) {
                    $this->info(__('Parsing board :board :key', ['board' => $board->name, 'key' => $board->jira_key]));
                    Artisan::call("jira:listener {$board->jira_key}");
                    Artisan::call("github:branches");
                    Artisan::call("github:commits {$board->id}");
                    $this->newLine();
                });
        } catch (Exception $e) {
            $this->newLine();
            $this->line($e->getMessage());
        }
    }
}
