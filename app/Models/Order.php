<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'costumer_id',
        'jewelry_id',
        'order_number',
        'remarks',
        'weight',
        'cost',
        'saved_price',
        'total_price',
        'paid_amount',
        'date_taken',
        'status',
        'created_by',
        'updated_by'
    ];

    public function costumer(): BelongsTo
    {
        return $this->belongsTo(Costumer::class);
    }

    public function jewelry(): BelongsTo
    {
        return $this->belongsTo(Jewelry::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
