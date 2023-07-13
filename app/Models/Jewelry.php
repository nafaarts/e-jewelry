<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jewelry extends Model
{
    use HasFactory;

    protected $fillable = [
        'price_id',
        'category_id',
        'supplier_id',
        'safe_box_id',
        'name',
        'jewelry_code',
        'weight',
        'cost',
        'photo',
        'status',
        'remarks'
    ];

    function price(): BelongsTo
    {
        return $this->belongsTo(Price::class);
    }

    function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    function safebox(): BelongsTo
    {
        return $this->belongsTo(SafeBox::class);
    }

    // function sellPrice(): float
    // {
    //     $pricePerGram   = Price::latest()->first()?->sell ?? 0;
    //     $totalPrice     = ($this->weight * $this->carat?->rate / 100) * $pricePerGram;
    //     $totalPrice     += $this->cost;

    //     return $totalPrice;
    // }

    // function buyPrice(): float
    // {
    //     $pricePerGram   = Price::latest()->first()?->buy ?? 0;
    //     $totalPrice     = ($this->weight * $this->carat?->rate / 100) * $pricePerGram;
    //     $totalPrice     += $this->cost;

    //     return $totalPrice;
    // }
}