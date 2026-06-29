<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRepository extends BaseRepository
{
    /**
     * UserRepository contructor.
     */
    public function __construct()
    {
        $this->model = (new User());
    }

    /**
     * Search Users
     * @param null|string $search
     * @return Collection
     */
    public function searchUser($search = null)
    {
        return $this->model->with(['informations'])
            ->whereNot('id', Auth::id())
            ->when(!empty($search), function($query) use($search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%');
            })->get();
    }
}