<?php

namespace App\Services;

use App\Repositories\ProfileViewsRepository;
use App\Repositories\UserInformationRepository;
use App\Repositories\UserPreferenceRepository;
use App\Repositories\UserRepository;
use App\Transformers\UserTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ProfileService extends BaseService
{
    /**
     * @var string
     */
    protected $viewDir = 'profile';

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepo;

    /**
     * @var UserInformationRepository
     */
    protected UserInformationRepository $informationRepo;

    /**
     * ProfileService constructor.
     */
    public function __construct()
    {
        $this->userRepo = (new UserRepository());
        $this->informationRepo = (new UserInformationRepository());
    }

    /**
     * Render Profile Page and return user related data
     * @param int|null $id
     * @return Inertia\Inertia
     */
    public function index($id = null)
    {
        $type = 'profile';
        if (empty($id)) {
            $user = Auth::user();
        } else {
            $type = 'visit';
            $user = $this->userRepo->find($id);
        }

        if ($user) {
            if ($user->id === Auth::id() && !empty($id)) {
                return redirect()->route('profile.get');
            }

            $userTransformer = (new UserTransformer());
            $userTransformer->setUser($user);
            $data = [
                'informations' => $userTransformer->informations(),
                'preferences'  => $userTransformer->preferences()
            ];
    
            if ($type === 'profile') {
                $data['views'] = $user->views->count();
            }
    
            return $this->view($this->viewDir . '.index', [
                'type' => $type,
                'data' => $data
            ]);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Update Profile Information
     * @param array $data
     * @return RedirectResponse
     */
    public function updateInformation(array $data) : RedirectResponse
    {
        $user = Auth::user();
        $userInformations = Arr::only($data, ['bio', 'address', 'contact_number', 'gender', 'birth_date']);
        if (empty($user->informations)) {
            $userInformations['user_id'] = $user->id;
            $result = $this->informationRepo->create($userInformations);
        } else {
            $result = $this->informationRepo->update($user->informations->id, $userInformations);
        }

        if (!$result) {
            return redirect()
                ->back()
                ->withErrors([
                    'message' => 'Something went wrong updating the informations'
                ]);
        }

        $preferences = Arr::get($data, 'preferences');
        if ($preferences) {
            $preferenceResult = $this->updateUserPreference($user->id, $preferences);
            if (!$preferenceResult)  {
                return redirect()
                    ->back()
                    ->withErrors([
                        'message' => 'Something went wrong updating the informations'
                    ]);
            }
        }

        return redirect()
            ->back()
            ->withInput();
    }

    /**
     * Update User Preferences
     * @param int $userId
     * @param array $data
     * @return mixed
     */
    private function updateUserPreference(int $userId, array $data)
    {
        $preferenceRepo = (new UserPreferenceRepository());
        $userPreference = $preferenceRepo->getPreferenceByUserId($userId);
        if ($userPreference) {
            $result = $preferenceRepo->update($userPreference->id, $data);
        } else {
            $data['user_id'] = $userId;
            $result = $preferenceRepo->create($data);
        }

        return $result;
    }

    /**
     * Add Visit Count
     * @param int $userId
     * @return JsonResponse
     */
    public function addVisitCount(int $userId) : JsonResponse
    {
        if (Auth::id() != $userId) {
            (new ProfileViewsRepository())->create([
                'user_id'   => $userId,
                'viewer_id' => Auth::id()
            ]);
        }
        
        return response()->json([
            'message' => 'success'
        ], 200);
    }
}