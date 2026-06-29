<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConversationParticipant extends Model
{
    /**
     * @var string
     */
    protected $table = 'conversation_participants';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id'
    ];

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')
            ->select('id', 'name', 'email');
    }
}