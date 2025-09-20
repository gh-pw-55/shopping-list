<?php

namespace App\Http\Controllers;

use App\Models\GroceryList;
use Inertia\Inertia;
use Inertia\Response;

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
}
