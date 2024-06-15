<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Probes;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProbePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->can('list probes')
            ? Response::allow()
            : Response::deny('You do not have the right role to show the probes');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Probes $probes): Response
    {
        return $user->currentTeam->id === $probes->team_id && $user->can('list probes')
            ? Response::allow()
            : Response::deny('You do not own this probe or you don\'t have the right role.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->can('create probes')
            ? Response::allow()
            : Response::deny('You do not have the role to create a probe');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Probes $probes): Response
    {
        return $user->currentTeam->id === $probes->team_id && $user->can('create probes')
            ? Response::allow()
            : Response::deny('You do not own this probe or you don\'t have the right role.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Probes $probes): Response
    {
        return $user->currentTeam->id === $probes->team_id && $user->can('delete probes')
            ? Response::allow()
            : Response::deny('You do not own this probe or you don\'t have the right role.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Probes $probes): Response
    {
        return $user->currentTeam->id === $probes->team_id && $user->can('delete probes')
            ? Response::allow()
            : Response::deny('You do not own this probe or you don\'t have the right role.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Probes $probes): Response
    {
        return $user->currentTeam->id === $probes->team_id && $user->can('delete probes')
            ? Response::allow()
            : Response::deny('You do not own this probe or you don\'t have the right role.');
    }
}
