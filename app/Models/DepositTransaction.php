<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'deposit_account_id',
        'transaction_number',
        'type',
        'category',
        'weight',
        'amount',
        'cost',
        'remarks',
        'is_canceled',
        'created_by',
        'updated_by',
    ];

    public function depositAccount()
    {
        return $this->belongsTo(DepositAccount::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
