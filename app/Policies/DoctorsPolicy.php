<?php

namespace App\Policies;

use App\Models\Doctors;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DoctorsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Doctors $doctors): bool
    {
        if (isset($user->roles[0]->name) && $user->roles[0]->name == "Student") {
            return false;
        }elseif(isset($user->roles[0]->name) && $user->roles[0]->name == "Doctor"){
            return false;
        }
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if (isset($user->roles[0]->name) && $user->roles[0]->name == "Student") {
            return false;
        }elseif(isset($user->roles[0]->name) && $user->roles[0]->name == "Doctor"){
            return false;
        }
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Doctors $doctors): bool
    {
        if (isset($user->roles[0]->name) && $user->roles[0]->name == "Student") {
            return false;
        }elseif(isset($user->roles[0]->name) && $user->roles[0]->name == "Doctor"){
            return false;
        }
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Doctors $doctors): bool
    {
        if (isset($user->roles[0]->name) && $user->roles[0]->name == "Student") {
            return false;
        }elseif(isset($user->roles[0]->name) && $user->roles[0]->name == "Doctor"){
            return false;
        }
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Doctors $doctors): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Doctors $doctors): bool
    {
        return true;
    }
}
