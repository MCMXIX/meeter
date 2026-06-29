<?php

namespace App\Transformers;

use App\Models\Conversation;
use Illuminate\Database\Eloquent\Collection;

class ConversationTransformer
{
    public function list(Collection $data) {
        $list = [];
        foreach ($data as $convo) {
            $conversation = $this->conversation($convo);
            if (!empty($conversation)) {
                $list[] = $conversation;
            }
        }

        return $list;
    }

    /**
     * Transform conversation before returning to front
     * @param Collection $data
     * @return array
     */
    public function conversation(Conversation $data) : array
    {
        if ($data) {
            $participant = [];
            if ($data->participant) {
                $participant = [
                    'user_id' => $data->participant->user_id,
                    'name'    => $data->participant->user->name,
                    'email'   => $data->participant->user->email
                ];

                if ($data->participant->user->informations) {
                    $participant['gender'] = $data->participant->user->informations->gender;
                    $participant['birth_date'] = $data->participant->user->informations->birth_date;
                }
            }
            
            $last_message = null;
            $created_at = $data->created_at;
            if ($data->lastMessage) {
                $last_message = $data->lastMessage->body ?? null;
                $created_at = $data->lastMessage->created_at ?? null;
            }

            return [
                'id'           => $data->id,
                'participant'  => $participant,
                'last_message' => $last_message,
                'created_at'   => $created_at
            ];
        }

        return [];
    }
}