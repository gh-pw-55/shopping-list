<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Grocery;
use App\Models\GroceryList;

class GroceryCompletionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_user_can_mark_a_grocery_item_as_completed()
    {
        $user = User::factory()->create();

        $list = GroceryList::factory()->for($user)->create();
        $grocery = Grocery::factory()->for($list)->create([
            'is_completed' => false,
        ]);

        $this->actingAs($user)
            ->patch(route('groceries.update', $grocery->id), [
                'is_completed' => true,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('groceries', [
            'id' => $grocery->id,
            'is_completed' => true,
        ]);
    }

    /** @test */
    public function test_user_can_mark_a_grocery_item_as_incomplete()
    {
        $user = User::factory()->create();

        $list = GroceryList::factory()->for($user)->create();
        $grocery = Grocery::factory()->for($list)->create([
            'is_completed' => true,
        ]);

        $this->actingAs($user)
            ->patch(route('groceries.update', $grocery->id), [
                'is_completed' => false,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('groceries', [
            'id' => $grocery->id,
            'is_completed' => false,
        ]);
    }

    public function test_user_can_delete_a_grocery_item()
    {
        $user = User::factory()->create();

        $list = GroceryList::factory()->for($user)->create();
        $grocery = Grocery::factory()->for($list)->create();

        $this->actingAs($user)
            ->delete(route('groceries.destroy', $grocery->id))
            ->assertRedirect();

        $this->assertDatabaseMissing('groceries', [
            'id' => $grocery->id,
        ]);
    }

    /** @test */
    public function test_cannot_add_duplicate_grocery_item_to_same_list()
    {
        $user = User::factory()->create();

        $list = GroceryList::factory()->for($user)->create();

        $this->actingAs($user)
            ->post(route('grocery.store'), [
                'name' => 'Basil',
                'quantity' => 1,
                'grocery_list_id' => $list->id,
            ])
            ->assertRedirect()
            ->assertSessionHas('success', 'Item added successfully!');

        $response = $this->actingAs($user)
            ->post(route('grocery.store'), [
                'name' => 'Basil',
                'quantity' => 2,
                'grocery_list_id' => $list->id,
            ]);

        $response->assertRedirect();

        $response->assertSessionHasErrors('name');

        $this->assertDatabaseCount('groceries', 1);

        $this->assertDatabaseHas('groceries', [
            'name' => 'Basil',
            'grocery_list_id' => $list->id,
            'quantity' => 1,
        ]);
    }

    /** @test */
    public function test_cannot_create_grocery_item_without_name()
    {
        $user = User::factory()->create();
        $list = GroceryList::factory()->for($user)->create();

        $response = $this->actingAs($user)
            ->post(route('grocery.store'), [
                'name' => '',
                'quantity' => 2,
                'grocery_list_id' => $list->id,
            ]);

        $response->assertSessionHasErrors('name');

        $this->assertDatabaseCount('groceries', 0);
    }

    public function test_cannot_create_grocery_item_with_negative_quantity(): void
    {
        $user = User::factory()->create();
        $list = GroceryList::factory()->for($user)->create();

        $response = $this->actingAs($user)
            ->post(route('grocery.store'), [
                'name' => 'Peter',
                'quantity' => -1,
                'grocery_list_id' => $list->id,
            ]);

        $response->assertSessionHasErrors('quantity');

        $this->assertDatabaseCount('groceries', 0);
    }

    /** @test */
    public function user_cannot_add_item_to_another_users_list()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $list = GroceryList::factory()->for($user1)->create();

        $response = $this->actingAs($user2)
            ->post(route('grocery.store'), [
                'name' => 'Basil',
                'quantity' => 2,
                'grocery_list_id' => $list->id,
            ]);

        $response->assertForbidden();

        $this->assertDatabaseMissing('groceries', [
            'name' => 'Basil',
            'grocery_list_id' => $list->id,
        ]);
    }
}
