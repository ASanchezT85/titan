<?php

namespace App\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commit extends Model
{
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'board_id',
        'developer_id',
        'number',
        'base',
        'title',
        'sha',
        'node_id',
        'html_url',
        'body',
        'date',
        'status',
        'linked',
        'response',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'response' => Json::class,
    ];

    /**
     * Get the developer that owns the card.
     */
    public function board_id(): BelongsTo
    {
        return $this->belongsTo(Board::class, 'board');
    }

    /**
     * Get the developer that owns the card.
     */
    public function developer(): BelongsTo
    {
        return $this->belongsTo(Developer::class, 'developer_id');
    }
}
