<?php

namespace App\Services;

use App\Repositories\PostRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class PostService extends BaseService
{
    /**
     * @var PostRepository
     */
    protected PostRepository $postRepo;

    /**
     * PostService constructor.
     */
    public function __construct()
    {
        $this->postRepo = (new PostRepository());
    }

    /**
     * Retrieve available posts
     * @param null|int $userId
     */
    public function getPost($userId)
    {
        $userId = ($userId) ? (int)$userId : $userId;
        $posts = $this->postRepo->getPost($userId);
        return response()->json(
            [
                'posts'       =>  $posts->getCollection()->values(),
                'next_cursor' => optional($posts->nextCursor())->encode(),
            ]
        , 200);
    }

    /**
     * Create new post
     * @param array $data
     * @return Inertia\Inertia
     */
    public function createPost(array $data)
    {
        $body = Arr::get($data, 'body');
        if (empty($body)) {
            return redirect()
                ->back()
                ->withErrors(['message' => 'Please provide your thoughts to create a new post.']);
        }

        $result = $this->postRepo->create([
            'user_id' => Auth::id(),
            'body'    => $body
        ]);

        if (!$result) {
            return redirect()
                ->back()
                ->withErrors([
                    'message' => 'Something went wrong with the request'
                ]);
        }

        return redirect()->back();
    }
}