<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'is_percent_cost',
        'photo',
        'status',
        'remarks'
    ];

    protected $appends = ['sell_price', 'buy_price'];

    public function price(): BelongsTo
    {
        return $this->belongsTo(Price::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function safebox(): BelongsTo
    {
        return $this->belongsTo(SafeBox::class);
    }

    public function saleItem(): BelongsTo
    {
        return $this->belongsTo(SaleItem::class);
    }

    public function getSellPriceAttribute(): float
    {
        if ($this->price()->doesntExist()) {
            return 0;
        }

        $totalPrice     =  ($this->weight / $this->price->weight) * ($this->price->sell_price + $this->price->cost);
        $costInPercent  =  $totalPrice * $this->cost / 100;
        $totalPrice     += $this->is_percent_cost ? $costInPercent : $this->cost;

        return round($totalPrice / 1000) * 1000;
    }

    public function getBuyPriceAttribute(): float
    {
        if ($this->price()->doesntExist()) {
            return 0;
        }

        $totalPrice     = ($this->weight / $this->price->weight) * $this->price->buy_price;
        // if cost not required, remove here!
        $costInPercent  =  $totalPrice * $this->cost / 100;
        $totalPrice     += $this->is_percent_cost ? $costInPercent : $this->cost;

        return round($totalPrice / 1000) * 1000;
    }
}
