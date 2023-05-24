<?php

namespace App\Console\Commands\Github;

use App\Models\Board;
use Illuminate\Console\Command;
use App\Traits\Github\HttpTrait;

class Branches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'github:branches';

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
                ->whereNotNull('github_ower')
                ->whereNotNull('github_repo')
                ->get()
                ->each(function (Board $board) {
                    $this->info(__('Start repo: :repo', ['repo' =>  $board->github_repo]));

                    $connection = new HttpTrait();
                    $response = $connection->get("repos/{$board->github_ower}/{$board->github_repo}/branches");
                    if ($response) {
                        foreach ($response->json() as $key => $value) {
                            $board->branches()->updateOrCreate(
                                [
                                    'name'      => $value['name'],
                                    'sha'       => $value['commit']['sha'],
                                ],
                                [
                                    'url'       => $value['commit']['url'],
                                    'protected' => (bool) $value['protected'],
                                    'response'  => $value,
                                ]
                            );
                        }
                    }
                });

            return;
        } catch (Exception $e) {
            $this->newLine();
            $this->line($e->getMessage());
        }
    }
}
