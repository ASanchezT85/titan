<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardCommit extends Model
{
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'board_id',
        'branch_id',
        'card_id',
        'commit_id',
    ];

    /**
     * Get the board that owns the card commit.
     */
    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class, 'board_id');
    }

    /**
     * Get the board that owns the card commit.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * Get the card that owns the card commit.
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'card_id');
    }

    /**
     * Get the commit that owns the card commit.
     */
    public function commit(): BelongsTo
    {
        return $this->belongsTo(Commit::class, 'commit_id');
    }
}
