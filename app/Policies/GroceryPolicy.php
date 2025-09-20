<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Grocery;

class GroceryPolicy
{
    /**
     * Create a new policy instance.
     */
    public function create(User $user, Grocery $grocery)
    {
        return $user->id === $grocery->groceryList->user_id;
    }

    public function delete(User $user, Grocery $grocery)
    {
        return $user->id === $grocery->groceryList->user_id;
    }
}
