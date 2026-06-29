<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository extends BaseRepository
{
    /**
     * PostRepository constructor.
     */
    public function __construct()
    {
        $this->model = (new Post());
    }

    /**
     * Retrieve avilable posts
     * @param null|int $userId
     * @return Collection
     */
    public function getPost($userId)
    {
        return $this->model->with(['user'])
            ->when($userId, function($query) use($userId) {
                $query->where('user_id', $userId);
            })
            ->latest()
            ->cursorPaginate(20);
    }
}