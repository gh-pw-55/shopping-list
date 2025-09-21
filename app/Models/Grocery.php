<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grocery extends Model
{
    use HasFactory;

    protected $table = 'groceries';

    protected $fillable = ['name', 'quantity', 'grocery_list_id', 'is_completed', 'position', 'price'];

    public function groceryList(): BelongsTo
    {
        return $this->belongsTo(GroceryList::class, 'grocery_list_id', 'id');
    }

    public function getPrice(): float
    {
        return $this->price / 100;
    }

    public function setPrice($value)
    {
        $this->price = (int) round($value * 100);
    }

    public function setPosition()
    {
        $this->position = Grocery::where('grocery_list_id', $this->grocery_list_id)->count() + 1;

        return $this->position;
    }
}
