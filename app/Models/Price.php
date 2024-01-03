<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'carat', 'rate', 'weight', 'sell_price', 'buy_price', 'cost', 'is_percent_cost', 'category', 'remarks'];
}
