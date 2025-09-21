<?php

namespace App\Http\Controllers;

use App\Models\GroceryList;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Grocery;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GroceryListController extends Controller
{
    use AuthorizesRequests;

    public function index(): Response
    {
        $groceryLists = auth()->user()->groceryLists()->get();

        return Inertia::render('GroceryList/Index', [
            'data' => $groceryLists,
        ]);
    }

    public function show(int $groceryList): Response
    {
        $groceryListModel = GroceryList::findOrFail($groceryList);
        // policy check - user can view
        $this->authorize('view', $groceryListModel);

        $list = auth()->user()
            ->groceryLists()
            ->with('groceries')
            ->findOrFail($groceryList);

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

    public function updateOrder(Request $request)
    {
        $newGrocerList = $request->input('order', []);

        foreach ($newGrocerList as $key => $groceryItem) {
            $grocery = Grocery::findOrFail($groceryItem['id']);
            $this->authorize('update', $grocery);

            $groceryItem['position'] = $key + 1;

            Grocery::where('id', $groceryItem['id'])->update(['position' => $key + 1]);
        }

        return redirect()->back()->with('success', 'Order updated!');
    }
}
