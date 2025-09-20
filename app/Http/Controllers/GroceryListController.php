<?php

namespace App\Http\Controllers;

use App\Models\GroceryList;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Grocery;

class GroceryListController extends Controller
{
    public function index(): Response
    {
        $groceryLists = auth()->user()->groceryLists()->get();

        return Inertia::render('GroceryList/Index', [
            'data' => $groceryLists,
        ]);
    }

    public function show(int $groceryList): Response
    {
        $list = GroceryList::with('groceries')->find($groceryList)->first();

        // policy check - user can view
        return Inertia::render('GroceryList/Show', [
            'data' => [
                'groceryList' => $list,
                'groceries' => $list->groceries,
            ]
        ]);
    }

    public function store(Request $request)
    {
        $user_id = auth()->user()->id;
        $validated_data = $request->validate([
            'title' => 'required|string'
        ]);

        $grocery = new GroceryList();
        $grocery->user_id = $user_id;
        $grocery->title = $validated_data['title'];

        $grocery->save();

        return back()->with('success', 'List added successfully!');
    }

    public function updateOrder(Request $request, GroceryList $groceryList)
    {
        $newGrocerList = $request->input('order', []);

        foreach ($newGrocerList as $key => $groceryItem) {
            $groceryItem['position'] = $key + 1;

            Grocery::where('id', $groceryItem['id'])->update(['position' => $key + 1]);
        }

        return redirect()->back()->with('success', 'Order updated!');
    }
}
