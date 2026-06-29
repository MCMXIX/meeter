<?php

namespace App\Transformers;

use App\Models\User;
use App\Models\UserInformation;
use App\Models\UserPreference;

class UserTransformer
{
    /**
     * @var User
     */
    protected User $user;

    /**
     * Set User Data
     * @return UserTransformer
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Return User Information
     * @return array
     */
    public function informations() : array
    {
        $data = $this->getInformations();
        return [
            'id'            => $this->user->id,
            'name'           => $this->user->name,
            'email'          => $this->user->email,
            'bio'            => $data->bio ?? '',
            'address'        => $data->address ?? null,
            'contact_number' => $data->contact_number ?? null,
            'gender'         => $data->gender ?? null,
            'birth_date'     => $data->birth_date ?? null
        ];
    }

    /**
     * Return User Preferences -- can extend to other preferences in the future
     * @return array
     */
    public function preferences() : array
    {
        $data = $this->getPreferences();
        if (!empty($data)) {
            // Can extend to other preferences
            return [
                'looking_for' => $data->looking_for
            ];
        }

        return [];
    }

    /**
     * Get User Informations
     * @return UserInformation|null
     */
    private function getInformations() : UserInformation | null
    {
        if ($this->user) {
            return $this->user->informations;
        }

        return null;
    }

    /**
     * Return User Preferences
     * @return UserPreference|null
     */
    private function getPreferences() : UserPreference | null
    {
        if ($this->user) {
            return $this->user->preferences;
        }

        return null;
    }
}