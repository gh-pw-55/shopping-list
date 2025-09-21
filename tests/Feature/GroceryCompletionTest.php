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

    public function test_cannot_add_duplicate_grocery_item_to_same_list()
    {
        $user = User::factory()->create();

        $list = GroceryList::factory()->for($user)->create();

        $this->actingAs($user)
            ->post(route('grocery.store'), [
                'name' => 'Basil',
                'quantity' => 1,
                'price' => 189,
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

    public function test_cannot_create_grocery_item_without_name()
    {
        $user = User::factory()->create();
        $list = GroceryList::factory()->for($user)->create();

        $response = $this->actingAs($user)->postJson(route('grocery.store'), [
            'name' => '',
            'quantity' => 2,
            'price' => 100,
            'grocery_list_id' => $list->id,
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'message' => 'The name field is required.',
        ]);
        $this->assertDatabaseCount('groceries', 0);
    }

    public function test_cannot_create_grocery_item_with_negative_quantity(): void
    {
        $user = User::factory()->create();
        $list = GroceryList::factory()->for($user)->create();

        $response = $this->actingAs($user)
            ->postJson(route('grocery.store'), [
                'name' => 'Chicken',
                'quantity' => -1,
                'price' => 450,
                'grocery_list_id' => $list->id,
            ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'message' => 'The quantity field must be at least 1.',
        ]);

        $this->assertDatabaseCount('groceries', 0);
    }

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

    public function test_guest_cannot_add_items()
    {
        $list = GroceryList::factory()->create();

        $response = $this->post(route('grocery.store'), [
            'grocery_list_id' => $list->id,
            'name' => 'Apples',
            'quantity' => 2,
            'price' => 10,
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_requires_name_to_be_unique_within_list()
    {
        $user = User::factory()->create();
        $list = GroceryList::factory()->for($user)->create();

        Grocery::factory()->create([
            'grocery_list_id' => $list->id,
            'name' => 'Apples',
            'quantity' => 2,
            'price' => 10,
        ]);

        $response = $this->actingAs($user)->postJson(route('grocery.store'), [
            'grocery_list_id' => $list->id,
            'name' => 'Apples',
            'quantity' => 2,
            'price' => 10,
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'message' => 'This item already exists in your grocery list.',
        ]);
    }

    public function test_user_can_add_new_item_to_their_list()
    {
        $user = User::factory()->create();
        $list = GroceryList::factory()->for($user)->create();

        $response = $this->actingAs($user)->post(route('grocery.store'), [
            'grocery_list_id' => $list->id,
            'name' => 'Bananas',
            'quantity' => 3,
            'price' => 5,
        ]);

        $response->assertSessionHas('success', 'Item added successfully!');

        $this->assertDatabaseHas('groceries', [
            'grocery_list_id' => $list->id,
            'name' => 'Bananas',
            'quantity' => 3,
            'price' => 500,
        ]);
    }

    public function test_guest_cannot_delete_grocery_item(): void
    {
        $grocery = Grocery::factory()->create();

        $response = $this->delete(route('groceries.destroy', $grocery));
        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('groceries', ['id' => $grocery->id]);
    }

    public function test_user_can_delete_grocery_item(): void
    {
        $user = User::factory()->create();
        $list = GroceryList::factory()->for($user)->create();

        $grocery = Grocery::factory()->for($list)->create();

        $response = $this->actingAs($user)->delete(route('groceries.destroy', $grocery));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Item removed successfully!');

        $this->assertDatabaseMissing('groceries', ['id' => $grocery->id]);
    }
}
