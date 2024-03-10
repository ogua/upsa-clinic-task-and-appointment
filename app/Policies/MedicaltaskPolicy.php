<?php

namespace App\Policies;

use App\Models\Medicaltask;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MedicaltaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return isset($user->roles[0]->name) && $user->roles[0]->name != "Student" ? true : false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Medicaltask $medicaltask): bool
    {
        return isset($user->roles[0]->name) && $user->roles[0]->name != "Student" ? true : false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return isset($user->roles[0]->name) && $user->roles[0]->name != "Student" ? true : false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Medicaltask $medicaltask): bool
    {
        return isset($user->roles[0]->name) && $user->roles[0]->name != "Student" ? true : false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Medicaltask $medicaltask): bool
    {
        return isset($user->roles[0]->name) && $user->roles[0]->name != "Student" ? true : false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Medicaltask $medicaltask): bool
    {
        return isset($user->roles[0]->name) && $user->roles[0]->name != "Student" ? true : false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Medicaltask $medicaltask): bool
    {
        return isset($user->roles[0]->name) && $user->roles[0]->name != "Student" ? true : false;
    }
}
