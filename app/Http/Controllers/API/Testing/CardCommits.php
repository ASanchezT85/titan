<?php

namespace App\Http\Controllers\API\Testing;

use Exception;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Testing\CardsCollection;
use Illuminate\Contracts\Database\Query\Builder;

class CardCommits extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {

            $cards = Card::query()
                ->select('cards.*', 'types.name as type_name', 'statuses.name as status_name')
                ->join('types', 'types.id', 'cards.type_id')
                ->join('statuses', 'statuses.id', 'cards.status_id')
                // ->with('commits')
                ->whereExists(function (Builder $query) {
                    $query->select(DB::raw(1))
                        ->from('card_commits')
                        ->whereColumn('card_commits.card_id', 'cards.id');
                })
                ->where('statuses.name', '<>', 'CIERRE DE MES')
                ->get();

            $cards = new CardsCollection($cards);

            return $this->sendResponse($cards, '----');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
