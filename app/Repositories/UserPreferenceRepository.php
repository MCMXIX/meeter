<?php

namespace App\Repositories;

use App\Models\UserPreference;

class UserPreferenceRepository extends BaseRepository
{
    /**
     * UserPreferenceRepository constructor.
     */
    public function __construct()
    {
        $this->model = (new UserPreference());
    }

    /**
     * Get User Preferences using User ID
     * @param int $userId
     * @return UserPreference
     */
    public function getPreferenceByUserId(int $userId) : UserPreference
    {
        return $this->model->where('user_id', $userId)->first();
    }
}