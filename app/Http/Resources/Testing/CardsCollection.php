<?php

namespace App\Http\Resources\Testing;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CardsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request) //: array
    {
        return $this->collection->transform(function ($item) {
            return [
                'id'                => $item->id,
                'type'              => $item->type_name,
                'identifier_id'     => $item->identifier_id,
                'identifier_key'    => $item->identifier_key,
                'title'             => $item->summary,
                'dev_cashship'      => $item->commits()->whereExists(function (Builder $query) {
                    $query->select(DB::raw(1))
                        ->from('branches')
                        ->whereColumn('branches.id', 'card_commits.branch_id')
                        ->where('branches.name', 'dev_cashship');
                })->get()->transform(function ($item) {
                    return $item->commit->sha;
                }),
                'qa'                => $item->commits()->whereExists(function (Builder $query) {
                    $query->select(DB::raw(1))
                        ->from('branches')
                        ->whereColumn('branches.id', 'card_commits.branch_id')
                        ->where('branches.name', 'qa');
                })->get()->transform(function ($item) {
                    return $item->commit->sha;
                }),
                'main'              => $item->commits()->whereExists(function (Builder $query) {
                    $query->select(DB::raw(1))
                        ->from('branches')
                        ->whereColumn('branches.id', 'card_commits.branch_id')
                        ->where('branches.name', 'qa');
                })->get()->transform(function ($item) {
                    return $item->commit->sha;
                }),
                'status'            => $item->status_name,
            ];
        });
    }
}
