<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Grocery;
use App\Models\GroceryList;

class GroceryPolicy
{
    /**
     * Create a new policy instance.
     */
    public function create(User $user, GroceryList $groceryList): bool
    {
        return $user->id === $groceryList->user_id;
    }

    public function delete(User $user, Grocery $grocery): bool
    {
        return $user->id === $grocery->groceryList->user_id;
    }

    public function update(User $user, Grocery $grocery): bool
    {
        return $user->id === $grocery->groceryList->user_id;
    }
}
