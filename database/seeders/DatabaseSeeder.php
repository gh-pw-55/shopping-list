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
                'position' => 1,
                'price' => 100,
            ],
            [
                'grocery_list_id' => $shoppingListId,
                'quantity' => rand(1, 4),
                'name' => 'Mozerella',
                'position' => 2,
                'price' => 250
            ],
            [
                'grocery_list_id' => $shoppingListId,
                'quantity' => rand(1, 4),
                'name' => 'Basil',
                'position' => 3,
                'is_completed' => 1,
                'price' => 85,
            ]
        ];

        foreach($items as $item) {
            Grocery::create($item);
        }
    }
}
