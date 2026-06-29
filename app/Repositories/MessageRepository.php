<?php

namespace App\Repositories;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageRepository extends BaseRepository
{
    /**
     * MessageRepository constructor.
     */
    public function __construct()
    {
        $this->model = (new Message());
    }

    /**
     * Get messages in conversation
     * @param int @id
     * @return Collection
     */
    public function getMessagesByConversationId(int $id)
    {
        return $this->model->with(['sender'])
            ->where('conversation_id', $id)
            ->whereHas('conversation.participants', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->latest()
            ->cursorPaginate(20);
    }
}