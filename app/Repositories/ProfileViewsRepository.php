<?php

namespace App\Repositories;

use App\Models\ProfileViews;

class ProfileViewsRepository extends BaseRepository
{
    /**
     * ProfileViewsRepository constructor.
     */
    public function __construct()
    {
        $this->model = (new ProfileViews());
    }
}