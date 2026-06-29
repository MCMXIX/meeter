<?php

namespace App\Http\Controllers;

use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * @var PostService
     */
    protected PostService $postService;

    public function __construct()
    {
        $this->postService = (new PostService);
    }

    /**
     * Retrieve post
     * @param null|int $userId
     * @return JsonResponse
     */
    public function getPost($userId = null) : JsonResponse
    {
        return $this->postService->getPost($userId);
    }

    /**
     * Create new post
     * @param Request $request
     * @return Inertia\Inertia
     */
    public function createPost(Request $request)
    {
        $data = $request->validate(['body' => 'required|string']);
        return $this->postService->createPost($data);
    }
}