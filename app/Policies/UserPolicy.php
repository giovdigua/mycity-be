<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;


class UserPolicy
{
    use HandlesAuthorization;

    //Only admin can update or delete user(Not other admin)
    public function delete(User $user, User $model)
    {
        return $user->role === 'admin' && $model->role === 'user';
    }

    public function update(User $user, User $model)
    {
        return $user->role === 'admin' && $model->role === 'user';
    }

    public function viewAny(User $user)
    {
        return $user->role === 'admin' || $user->role === 'user';
    }
}
