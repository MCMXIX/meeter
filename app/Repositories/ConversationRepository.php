<?php

namespace App\Repositories;

use App\Models\Conversation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class ConversationRepository extends BaseRepository
{
    /**
     * ConversationRepository constructor.
     */
    public function __construct()
    {
        $this->model = (new Conversation());
    }

    /**
     * Retrieve user conversations
     * @param int $userId
     * @param string|null $search
     * @return Collection
     */
    public function getUserConversations(int $userId, $search = null)
    {
        return $this->model->with(['participant', 'participant.user.informations', 'lastMessage'])
            ->whereHas('participant.user', function($query) use($search, $userId) {
                if (!empty($search)) {
                    $query->where(function($query) use($search) {
                        $query->where('name', "LIKE", '%' . $search . '%')
                            ->orWhere('email', "LIKE", '%' . $search . '%');
                    })
                    ->whereNot('id', $userId);
                }
            })
            ->whereHas('participants', function($query) use($userId) {
                $query->where('user_id', $userId);
            })
            ->get();
    }

    /**
     * Retrieve Auth User Conversation with Other User
     * @param int $userId
     * @return Conversation|null
     */
    public function getConversationByUserId(int $userId)
    {
        return $this->model->with(['participant'])
            ->whereHas('participant', function($query) use($userId) {
                $query->where('user_id', $userId);
            })
            ->whereHas('participants', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->first();
    }
}