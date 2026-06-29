<?php

namespace App\Traits;

use Inertia\Inertia; 
use Illuminate\Http\Request;
use Auth;

trait InertiaResponseTrait
{
    /**
     * Additional data on top of request data response
     *
     * @var Array
     */
    protected $addendums = []; 

    /**
     * Builds Inertia view
     *
     * @param String $view
     * @param Data $data
     * @return Inertia\Inertia
     */
    public function view($view, $data = [])
    { 
        if (Auth::check()) $this->set_addendum();

        return Inertia::render(
            $this->build_view($view), 
            $this->build_data($data)
        );
    }

    /**
     * Merges request data response with required addendum data
     *
     * @param Array $primary_data
     * @return Array
     */
    public function build_data($primary_data)
    {
        return array_merge($this->addendums, $primary_data);
    }

    /**
     * Builds view path based on inertia
     *
     * @param String $view
     * @return String
     */
    public function build_view($view)
    { 
        return implode(
            '/',
            array_map(fn($str): string => ucwords($str), explode('.', $view))
        );
    }

    /**
     * Sets addendum data to inertia response
     *
     * @return void
     */
    public function set_addendum()
    {
        $user = Auth::user();

        $this->addendums = [
            'role' => $user->role,
            'user' => [ 
                'id'     => $user->id,
                'name'   => $user->name,
                'email'  => $user->email
            ]
        ];
    }
}