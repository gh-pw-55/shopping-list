<?php

namespace App\Http\Controllers;

use App\Models\Grocery;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GroceryController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', function ($attribute, $value, $fail) use ($request) {
                $exists = Grocery::where('grocery_list_id', $request->grocery_list_id)
                    ->where('name', $value)
                    ->exists();
                if ($exists) {
                    $fail('This item already exists in your grocery list.');
                }
            }],
            'quantity' => 'required|int|min:1',
            'grocery_list_id' => 'required|exists:groceries_lists,id'

        ]);

        $grocery = new Grocery($request->only(['name', 'quantity', 'grocery_list_id']));
        $this->authorize('create', $grocery);

        $grocery->save();
        return back()->with('success', 'Item added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Grocery $grocery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grocery $grocery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grocery $grocery)
    {
        $this->authorize('create', $grocery);

        // only interested in updating the iscompleted val right now.
        $validation = $request->validate([
            'is_completed' => 'required|boolean',
        ]);

        $grocery->update($validation);
        $grocery->refresh();

        $completedString = "$grocery->name is updated";
        return back()->with('success', $completedString);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grocery $grocery)
    {
        // Authorize: user can only delete if they own the list
        $this->authorize('delete', $grocery);

        $grocery->delete();

        return back()->with('success', 'Item removed successfully!');
    }
}
