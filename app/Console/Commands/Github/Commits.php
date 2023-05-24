<?php

namespace App\Console\Commands\Github;

use App\Models\Board;
use App\Models\Branch;
use App\Models\Commit;
use Exception;
use App\Models\Developer;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use App\Traits\Github\HttpTrait;
use Symfony\Component\HttpFoundation\Response;

class Commits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'github:commits {board}';

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

            $board = Board::find($this->argument('board'));
            if (!$board)
                throw new Exception("Board not found", Response::HTTP_NOT_FOUND);

            $owner = $board->github_ower;
            $repo = $board->github_repo;

            if (!$owner || !$repo)
                throw new Exception("Nothing to look for", Response::HTTP_NOT_FOUND);

            $mainBranches = $board->branches()->where('main', true)->get();
            $mainBranches = array_column(($mainBranches)->toArray(), 'name');

            $number = 1;
            $counterFalse = 0;
            $commit = Commit::query()->latest()->first();
            if ($commit)
                $number = $commit->number + 1;

            while (true) {

                if ($counterFalse >= 5)
                    break;

                $connection = new HttpTrait();
                $response = $connection->get("repos/{$owner}/{$repo}/pulls/{$number}");
                if ($response) {
                    $pull = $response->json();
                    $branche = $pull['base']['ref'];
                    $nodeId = $pull['node_id'];
                    $htmlUrl = $pull['html_url'];
                    $number = $pull['number'];
                    $status = $pull['state'];
                    $title = $pull['title'];
                    $body = $pull['body'];
                    $mergedAt = $pull['merged_at'];
                    $mergeCommitSha = $pull['merge_commit_sha'];

                    $userLogin = $pull['user']['login'];
                    $developer = Developer::where('github_name', $userLogin)->first();
                    if (!$developer) {
                        $connection = new HttpTrait();
                        $response = $connection->get("users/{$userLogin}");
                        if ($response) {
                            $user = $response->json();
                            $developer = Developer::updateOrCreate(
                                [
                                    'identifier_id' => $user['node_id'],
                                ],
                                [
                                    'email'         => $user['email'],
                                    'name'          => $user['name'],
                                    'github_name'   => $userLogin,
                                    'github_avatar' => $user['avatar_url'],
                                ]
                            );
                        }
                    }

                    Commit::create([
                        'board_id'      => $board->id,
                        'sha'           => $mergeCommitSha,
                        'node_id'       => $nodeId,
                        'number'        => $number,
                        'base'          => $branche,
                        'title'         => $title,
                        'developer_id'  => $developer->id ?? null,
                        'html_url'      => $htmlUrl,
                        'body'          => $body,
                        'date'          => date('Y-m-d H:i:s', strtotime($mergedAt)),
                        'status'        => $status,
                        'response'      => $pull,
                    ]);
                } else {
                    $this->info("Pull: {$number} not found... ({$counterFalse})");
                    $counterFalse++;
                }

                $number++;
            }

            return;
        } catch (Exception $e) {
            $this->newLine();
            $this->line($e->getMessage());
        }
    }
}
