<?php

namespace App\Policies;

use App\Todo;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TodoPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    //pour la restriction concernant supprimer et editer
    public function delete(User $user, Todo $todo)
    {
        # code...
        return $user->id === $todo->creator_id;
    }
    public function edit(User $user, Todo $todo)
    {
        # code...
        return $user->id === $todo->creator_id;
    }
}
