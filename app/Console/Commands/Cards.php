<?php

namespace App\Console\Commands;

use App\Models\Branch;
use Exception;
use App\Models\Card;
use App\Models\CardCommit;
use App\Models\Commit;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class Cards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'titan:cards';

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
            $cards = Card::query()
                ->get()
                ->each(function (Card $card) {

                    $branches = Branch::query()->get();

                    $summary = Str::replace('|', ' ', $card->summary);
                    $summary = Str::slug($summary);
                    $title = "{$card->identifier_key}-{$summary}";

                    $commits = Commit::query()
                        ->select('commits.id', 'commits.title', 'commits.sha', 'commits.base', 'commits.status', 'commits.date')
                        ->where('commits.board_id', $card->board_id)
                        ->where('commits.status', 'closed')
                        ->where('commits.base', 'dev_cashship')
                        ->get();

                    if ($commits->count() > 0) {
                        foreach ($commits as $key => $commit) {
                            $base = collect($branches)->firstWhere('name', $commit->base);
                            if ($base) {
                                $commitTitle = Str::slug($commit->title);
                                similar_text(Str::slug($title), $commitTitle, $perc);
                                if ($perc > 90 && Str::contains($commit->title, $card->identifier_key)) {
                                    CardCommit::updateOrCreate(
                                        [
                                            'card_id'       => $card->id,
                                            'commit_id'     => $commit->id,
                                        ],
                                        [
                                            'board_id'      => $card->board_id,
                                            'branch_id'     => $base->id,
                                        ]
                                    );

                                    $commit->linked = true;
                                    $commit->save();
                                }
                            }
                        }
                    }

                    $commits = Commit::query()
                        ->select('commits.id', 'commits.title', 'commits.sha', 'commits.base', 'commits.status', 'commits.date')
                        ->where('commits.board_id', $card->board_id)
                        ->where('commits.status', 'closed')
                        ->where('commits.base', 'qa')
                        ->get();

                    if ($commits->count() > 0) {
                        foreach ($commits as $key => $commit) {
                            $base = collect($branches)->firstWhere('name', $commit->base);
                            if ($base) {
                                $key = explode('-', $card->identifier_key);
                                $key = (int) end($key);
                                $title = "release-{$key}-to-qa";
                                similar_text($title, $commit->title, $perc);
                                if ($perc > 97 && Str::containsAll($title, ['release', $key, 'to', 'qa'])) {
                                    CardCommit::updateOrCreate(
                                        [
                                            'card_id'       => $card->id,
                                            'commit_id'     => $commit->id,
                                        ],
                                        [
                                            'board_id'      => $card->board_id,
                                            'branch_id'     => $base->id,
                                        ]
                                    );

                                    $commit->linked = true;
                                    $commit->save();
                                }
                            }
                        }
                    }

                    $commits = Commit::query()
                        ->select('commits.id', 'commits.title', 'commits.sha', 'commits.base', 'commits.status', 'commits.date')
                        ->where('commits.board_id', $card->board_id)
                        ->where('commits.status', 'closed')
                        ->where('commits.base', 'main')
                        ->get();

                    if ($commits->count() > 0) {
                        foreach ($commits as $key => $commit) {
                            $base = collect($branches)->firstWhere('name', $commit->base);
                            if ($base) {
                                $key = explode('-', $card->identifier_key);
                                $key = (int) end($key);
                                $title = "release-{$key}-to-main";
                                similar_text($title, $commit->title, $perc);
                                if ($perc > 97 && Str::containsAll($title, ['release', $key, 'to', 'main'])) {
                                    CardCommit::updateOrCreate(
                                        [
                                            'card_id'       => $card->id,
                                            'commit_id'     => $commit->id,
                                        ],
                                        [
                                            'board_id'      => $card->board_id,
                                            'branch_id'     => $base->id,
                                        ]
                                    );

                                    $commit->linked = true;
                                    $commit->save();
                                }
                            }
                        }
                    }
                });
        } catch (Exception $e) {
            $this->newLine();
            $this->line($e->getMessage());
        }
    }
}
