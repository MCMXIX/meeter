<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    /**
     * @var string
     */
    protected $table = 'messages';

    /**
     * @var array
     */
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'body'
    ];

    /**
     * @return BelongsTo
     */
    public function sender() : BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'id')
            ->select('id', 'name', 'email');
    }

    /**
     * @return BelongsTo
     */
    public function conversation() : BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'conversation_id', 'id');
    }
}