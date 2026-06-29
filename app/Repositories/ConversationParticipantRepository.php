<?php

namespace App\Repositories;

use App\Models\ConversationParticipant;

class ConversationParticipantRepository extends BaseRepository
{
    /**
     * ConversationParticipantRepository constructro.
     */
    public function __construct()
    {
        $this->model = (new ConversationParticipant());
    }
}