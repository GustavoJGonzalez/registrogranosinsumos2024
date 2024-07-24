<?php

namespace App\Policies;

use App\Models\User;
use App\Models\RecepcionGranos;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecepcionGranosPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_recepcion::granos');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RecepcionGranos $recepcionGranos): bool
    {
        return $user->can('view_recepcion::granos');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_recepcion::granos');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RecepcionGranos $recepcionGranos): bool
    {
        return $user->can('update_recepcion::granos');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RecepcionGranos $recepcionGranos): bool
    {
        return $user->can('delete_recepcion::granos');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_recepcion::granos');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, RecepcionGranos $recepcionGranos): bool
    {
        return $user->can('force_delete_recepcion::granos');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_recepcion::granos');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, RecepcionGranos $recepcionGranos): bool
    {
        return $user->can('restore_recepcion::granos');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_recepcion::granos');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, RecepcionGranos $recepcionGranos): bool
    {
        return $user->can('replicate_recepcion::granos');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_recepcion::granos');
    }
}
