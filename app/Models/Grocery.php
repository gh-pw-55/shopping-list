<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grocery extends Model
{
    protected $table = 'groceries';

    protected $fillable = ['name', 'quantity', 'grocery_list_id', 'is_completed'];

    public function groceryList(): BelongsTo
    {
        return $this->belongsTo(GroceryList::class, 'grocery_list_id', 'id');
    }
}
