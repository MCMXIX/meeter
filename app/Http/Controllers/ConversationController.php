<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ConversationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ConversationController extends Controller
{
    /**
     * @var ConversationService
     */
    protected ConversationService $conversationService;

    /**
     * ConversationController constructor.
     */
    public function __construct()
    {
        $this->conversationService = (new ConversationService);
    }

    /**
     * Render User Conversations Page
     * @param Request $request
     * @param int|null $conversationId
     * @return Inertia\Inertia
     */
    public function index(Request $request, $conversationId = null)
    {
        return $this->conversationService->index($request->search ?? null, (int)$conversationId);
    }

    /**
     * Get Messages in a conversation
     * @param int $id
     * @return JsonResponse
     */
    public function getMessages(int $id) : JsonResponse
    {
        return $this->conversationService->getMessages($id);
    }

    /**
     * Check existing conversation, create if none eixsting
     * @param int $id
     * @return Inertia\Inertia
     */
    public function checkConversation(int $id)
    {
        return $this->conversationService->checkConversation($id);
    }

    public function sendMessage(Request $request)
    {
        $data = $request->validate([
            'conversation_id' => 'required|int',
            'message'         => 'nullable|string'
        ]);
        return $this->conversationService->createMessage($data);
    }
}