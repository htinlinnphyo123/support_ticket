<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getAllUsers(User $user)
    {
        $query = User::query()
            ->with('organisation:id,name')
            ->select('id', 'name', 'email', 'phone', 'type', 'status', 'organisation_id');

        if ($user->type->value === 'employee') {
            $query->where('organisation_id', $user->organisation_id);
        }

        return $query->paginate(10);
    }

    public function createUser(array $data, User $creator)
    {
        if ($creator->type->value === 'employee') {
            $data['organisation_id'] = $creator->organisation_id;
            $data['type'] = 'employee';
        } else {
            if (isset($data['type']) && $data['type'] === 'agent') {
                $data['organisation_id'] = null;
            }
        }

        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function updateUser(User $user, array $data, User $updater)
    {
        if ($updater->type->value === 'employee') {
            $data['organisation_id'] = $updater->organisation_id;
            $data['type'] = 'employee';
        } else {
            if (isset($data['type']) && $data['type'] === 'agent') {
                $data['organisation_id'] = null;
            }
        }

        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        return $user;
    }

    public function deleteUser(User $user)
    {
        return $user->delete();
    }
}
