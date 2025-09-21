<?php

namespace App\Policies;

use App\Models\User;
use App\Models\GroceryList;

class GroceryListPolicy
{
    public function view(User $user, GroceryList $groceryList): bool
    {
        return $user->id === $groceryList->user_id;
    }
}
