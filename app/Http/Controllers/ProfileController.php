<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UserInformationRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Services\ProfileService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ProfileController extends Controller
{
    /**
     * @var ProfileService
     */
    private $profileService;

    /**
     * ProfileController constructor.
     */
    public function __construct()
    {
        $this->profileService = (new ProfileService);
    }

    /**
     * Render Authenticated User Profile
     * @param int|null $id
     * @return Inertia\Inertia
     */
    public function index($id = null)
    {
        return $this->profileService->index($id);
    }

    /**
     * Update User Informations in Profile Page
     * @param UserInformationRequest $request
     * @return Inertia\Inertia
     */
    public function update(UserInformationRequest $request)
    {
        return $this->profileService->updateInformation($request->validated());
    }

    /**
     * Add Visit Count
     * @param Request $request
     * @return JsonResponse
     */
    public function addVisitCount(Request $request) : JsonResponse
    {
        $validRequest = $request->validate(['user_id' => 'required|int']);
        return $this->profileService->addVisitCount(Arr::get($validRequest, 'user_id'));
    }
}
