<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class Conversation extends Model
{
    /**
     * @var string
     */
    protected $table = 'conversations';

    public function participant() : HasOne
    {
        return $this->hasOne(ConversationParticipant::class, 'conversation_id', 'id')
            ->whereNot('user_id', Auth::id());
    }

    /**
     * @return HasMany
     */
    public function participants() : HasMany
    {
        return $this->hasMany(ConversationParticipant::class, 'conversation_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function messages() : HasMany
    {
        return $this->hasMany(Message::class, 'conversation_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function lastMessage() : HasOne
    {
        return $this->hasOne(Message::class, 'conversation_id', 'id')->orderBy('id', 'DESC');
    }
}