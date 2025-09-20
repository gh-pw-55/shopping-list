<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grocery extends Model
{
    use HasFactory;

    protected $table = 'groceries';

    protected $fillable = ['name', 'quantity', 'grocery_list_id', 'is_completed', 'position'];

    public function groceryList(): BelongsTo
    {
        return $this->belongsTo(GroceryList::class, 'grocery_list_id', 'id');
    }
}
