<?php

namespace App\Http\Controllers;

use App\Models\Grocery;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

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
        // check that the user belongs to the grocery list.
        $request->validate([
            'name' => 'required|string',
            'quantity' => 'required|int',
            'grocery_list_id' => 'required|exists:groceries_lists,id'
        ]);

        // Authorize before saving
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
        //
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
