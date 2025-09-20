<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\GroceryList;
use App\Models\Grocery;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $userId = $user->id;

        $list = GroceryList::create([
            'user_id' => $userId,
            'title' => 'Shopping',
        ]);

        $shoppingListId = $list->id;

        $items = [
            [
                'grocery_list_id' => $shoppingListId,
                'quantity' => rand(1, 4),
                'name' => 'Tomatoes',
            ],
            [
                'grocery_list_id' => $shoppingListId,
                'quantity' => rand(1, 4),
                'name' => 'Basil',
            ],
            [
                'grocery_list_id' => $shoppingListId,
                'quantity' => rand(1, 4),
                'name' => 'Mozerella',
            ]
        ];

        foreach($items as $item) {
            Grocery::create($item);
        }
    }
}
