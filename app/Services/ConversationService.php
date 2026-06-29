<?php

namespace App\Services;

use App\Repositories\ConversationParticipantRepository;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use App\Transformers\ConversationTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConversationService extends BaseService
{
    /**
     * @var string
     */
    protected $viewDir = 'conversations';

    /**
     * @var ConversationRepository
     */
    protected ConversationRepository $conversationRepo;

    /**
     * @var MessageRepository
     */
    protected MessageRepository $messageRepo;

    /**
     * ConversationService constructor.
     */
    public function __construct()
    {
        $this->conversationRepo = (new ConversationRepository());
        $this->messageRepo = (new MessageRepository());
    }

    /**
     * Render User Conversations Page
     * @param string|null $search
     * @param null|int $conversationId
     * @return Inertia\Inertia
     */
    public function index($search = null, $conversationId = null)
    {
        $user = Auth::user();
        if ($user) {
            $conversations = $this->conversationRepo->getUserConversations($user->id, $search);
            return $this->view($this->viewDir . '.index', [
                'data' => [
                    'conversations' => (new ConversationTransformer)->list($conversations),
                    'active_id'     => $conversationId
                ]
            ]);
        }

        return redirect()->route('login');
    }

    /**
     * Retrieve Messages in a conversation
     * @param int $id
     * @return JsonResponse
     */
    public function getMessages(int $id) : JsonResponse
    {
        $messages = $this->messageRepo->getMessagesByConversationId($id);

        return response()->json([
            'messages'    => $messages->getCollection()->reverse()->values(),
            'next_cursor' => optional($messages->nextCursor())->encode(),
        ]);
    }

    /**
     * Check existing conversation, create if none
     * @param int $userId
     * @return Inertia\Inertia
     */
    public function checkConversation(int $userId)
    {
        $activeConvo = $this->conversationRepo->getConversationByUserId((int)$userId);
        if (!$activeConvo) {
            $activeConvo = $this->conversationRepo->create([]);
            (new ConversationParticipantRepository())->insert([
                [
                    'conversation_id' => $activeConvo->id,
                    'user_id'         => $userId
                ],
                [
                    'conversation_id' => $activeConvo->id,
                    'user_id'         => Auth::id()
                ]
            ]);
        }

        return redirect()->route('conversation.get', ['id' => $activeConvo->id]);
    }

    /**
     * Send/Create new message
     * @param array $data
     * @return Inertia\Inertia
     */
    public function createMessage(array $data)
    {
        if (Arr::get($data, 'message')) {
            $result = $this->messageRepo->create([
                'conversation_id' => $data['conversation_id'],
                'sender_id'       => Auth::id(),
                'body'            => $data['message']
            ]);
    
            if (!$result) {
                return redirect()
                    ->back()
                    ->withErrors(['message' => 'something went wrong sending message']);
            }
        }

        return redirect()
            ->back()
            ->withInput();
    }
}