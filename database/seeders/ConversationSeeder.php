<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConversationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all()->values();

        $sampleMessages = [
            'Hey!',
            'Hello 😊',
            'How are you?',
            'What are you doing?',
            'How was your day?',
            'Nice meeting you!',
            'Want to grab coffee?',
            'That sounds great!',
            '😂😂😂',
            'See you soon!',
            'Good morning!',
            'Good night!',
            'I love dogs.',
            'What are your hobbies?',
            'Where are you from?',
        ];

        for ($i = 0; $i < $users->count(); $i++) {
            for ($j = $i + 1; $j < $users->count(); $j++) {
                // 35% chance that these two users have a conversation
                if (!fake()->boolean(35)) {
                    continue;
                }

                DB::transaction(function () use ($users, $i, $j, $sampleMessages) {
                    $conversation = Conversation::create();
                    ConversationParticipant::insert([
                        [
                            'conversation_id' => $conversation->id,
                            'user_id'         => $users[$i]->id,
                        ],
                        [
                            'conversation_id' => $conversation->id,
                            'user_id'         => $users[$j]->id,
                        ],
                    ]);

                    $time = now()->subDays(rand(1, 90));
                    $messageCount = rand(5, 40);
                    for ($k = 0; $k < $messageCount; $k++) {
                        Message::create([
                            'conversation_id' => $conversation->id,
                            'sender_id' => fake()->randomElement([
                                $users[$i]->id,
                                $users[$j]->id,
                            ]),
                            'body' => fake()->randomElement($sampleMessages),
                            'created_at' => $time,
                            'updated_at' => $time,
                        ]);

                        // Next message is 1–120 minutes later
                        $time = $time->copy()->addMinutes(rand(1, 120));
                    }

                    $conversation->update([
                        'updated_at' => $time,
                    ]);
                });
            }
        }
    }
}
