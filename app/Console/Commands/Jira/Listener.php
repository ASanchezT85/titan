<?php

namespace App\Console\Commands\Jira;

use Exception;
use App\Models\Card;
use App\Models\Type;
use App\Models\Board;
use App\Models\Status;
use App\Models\Priority;
use App\Models\Developer;
use App\Traits\Jira\HttpTrait;
use Illuminate\Console\Command;

class Listener extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jira:listener {key}';

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

            $keyBoard = $this->argument('key');
            $keyNumber = 1;
            $lastCard = Card::query()->latest()->first();
            if ($lastCard) {
                $key = explode('-', $lastCard->identifier_key);
                $keyNumber = (int) end($key) + 1;
            }

            $counterFalse = 0;
            while (true) {

                if ($counterFalse >= 5)
                    break;

                $connection = new HttpTrait();
                $response = $connection->get("issue/{$keyBoard}-{$keyNumber}?fields=project,issuetype,assignee,summary,description,status,priority");
                if (!$response)
                    break;

                if (isset($response['status']) && !$response['status']) {
                    foreach ($response['error_messages'] as $value) {
                        if ($value === 'La incidencia no existe o no tienes permiso para verla.') {
                            $counterFalse++;
                            $this->info("CBE-{$keyNumber} not found... ({$counterFalse})");
                        }
                    }
                } else {

                    $type = Type::where('identifier_id', $response['fields']['issuetype']['id'])->first();
                    if (!$type) {
                        $type = Type::create([
                            'identifier_id' => $response['fields']['issuetype']['id'],
                            'name'          => $response['fields']['issuetype']['name'],
                            'description'   => $response['fields']['issuetype']['description'],
                            'icon'          => $response['fields']['issuetype']['iconUrl'],
                        ]);
                    }

                    $developer = null;
                    if (isset($response['fields']['assignee'])) {
                        $developer = Developer::where('identifier_id', $response['fields']['assignee']['accountId'])->first();
                        if (!$developer) {
                            $assignee = $response['fields']['assignee'];
                            $developer = Developer::create([
                                'identifier_id' => $assignee['accountId'] ?? null,
                                'email'         => $assignee['emailAddress'] ?? null,
                                'name'          => $assignee['displayName'] ?? null,
                            ]);
                        }
                    }

                    $priority = Priority::where('identifier_id', $response['fields']['priority']['id'])->first();
                    if (!$priority) {
                        $priority = Priority::create([
                            'identifier_id' => $response['fields']['priority']['id'],
                            'name'          => $response['fields']['priority']['name'],
                            'icon'          => $response['fields']['priority']['iconUrl'],
                        ]);
                    }

                    $status = Status::where('identifier_id', $response['fields']['status']['id'])->first();
                    if (!$status) {
                        $status = Status::create([
                            'identifier_id' => $response['fields']['status']['id'],
                            'name'          => $response['fields']['status']['name'],
                            'icon'          => $response['fields']['status']['iconUrl'],
                        ]);
                    }


                    $board = Board::query()
                        ->where('jira_id', $response['fields']['project']['id'])
                        ->where('jira_key', $response['fields']['project']['key'])
                        ->first();

                    $board->cards()->create([
                        'type_id'           => $type->id,
                        'identifier_id'     => $response['id'],
                        'identifier_key'    => $response['key'],
                        'summary'           => $response['fields']['summary'],
                        'priority_id'       => $priority->id,
                        'developer_id'      => $developer ? $developer->id : null,
                        'status_id'         => $status->id,
                        'response'          => $response,
                    ]);

                    $this->info('Created card: ' . $response['key']);

                    $counterFalse = 0;
                }
                $keyNumber++;
            }

            $this->newLine();
            $this->line('Finished the importation...');

            return;
        } catch (Exception $e) {
            $this->newLine();
            $this->line($e->getMessage());
        }
    }
}
