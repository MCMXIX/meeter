<?php

namespace App\Repositories;

use App\Models\UserInformation;

class UserInformationRepository extends BaseRepository
{
    /**
     * UserInformationRepository constructor.
     */
    public function __construct()
    {
        $this->model = (new UserInformation());
    }
}