<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Arr;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Project::truncate();
        Schema::enableForeignKeyConstraints();

        $projects = [
            [
                'name'          => 'CashShip - Cashela',
                'description'   => 'Remesas, Recargas, pago de facturas y otros',
                'file'          => 'https://miamineversleeps.atlassian.net/rest/api/3/universal_avatar/view/type/project/avatar/10601',
                'status'        => 'ACTIVE',
                'providers'     => [
                    [
                        'name'          => 'GitHub',
                        'status'        => 'ON',
                        'keys'          => [
                            [
                                'key'       => 'URL',
                                'value'     => 'https://api.github.com'
                            ],
                            [
                                'key'       => 'TOKEN',
                                'value'     => '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'
                            ]
                        ]
                    ],
                    [
                        'name'          => 'Jira',
                        'status'        => 'ON',
                        'keys'          => [
                            [
                                'key'       => 'URL',
                                'value'     => 'https://miamineversleeps.atlassian.net/rest/api/3'
                            ],
                            [
                                'key'       => 'USER',
                                'value'     => 'ajstalito@gmail.com'
                            ],
                            [
                                'key'       => 'TOKEN',
                                'value'     => 'ATATT3xFfGF0EIzXKzTk5E4jETcWHm_lFavpg6P0SZMtOhYk5LDcAu0AqnJTJOmMlcxId3FsRIegFRtXvBYlyLQClWX0ZSIzf3dbh6kkE1AEfZUQwen18c8mu3EW-jKvTPJEUxcd7mbJ_PyMq20anxwhsAjKqXKl3T_cJ_quTaqqgGjjt3xbAAs=2A2725EE'
                            ]
                        ]
                    ],
                ],
                'boards'        => [
                    [
                        'name'          => 'CASHSHIP-BACKEND',
                        'jira_id'       => '10108',
                        'jira_key'      => 'CBE',
                        'github_ower'   => 'hectorgarcia83',
                        'github_repo'   => 'CashShip',
                        'status'        => 'ACTIVE',
                    ]
                ]
            ]
        ];

        foreach ($projects as $key => $project) {
            $providers = $project['providers'];
            $boards = $project['boards'];

            $project['slug'] = Str::slug($project['name']);
            $project = Project::create(Arr::only($project, ['name', 'slug', 'description', 'file', 'status']));

            foreach ($providers as $key => $provider) {

                $keys = $provider['keys'];

                $provider['slug'] = Str::slug($provider['name']);;
                $provider = $project->providers()->create(Arr::only($provider, ['name', 'slug', 'status']));

                foreach ($keys as $key) {
                    $provider->keys()->create($key);
                }
            }

            foreach ($boards as $key => $board) {
                $project->boards()->create($board);
            }
        }
    }
}
