<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroceryList extends Model
{
    protected $table = 'groceries_lists';

    protected $fillable = ['title', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function groceries(): HasMany
    {
        return $this->hasMany(Grocery::class, 'grocery_list_id', 'id');
    }
}
