<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'costumer_id',
        'account_number',
        'is_active',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $appends = ['gold_balance', 'money_balance'];

    public function costumer()
    {
        return $this->belongsTo(Costumer::class);
    }

    public function transactions()
    {
        return $this->hasMany(DepositTransaction::class);
    }

    protected function goldBalance(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->transactions()->selectRaw("
                    SUM(CASE WHEN type = 'CREDIT' THEN weight ELSE 0 END) -
                    SUM(CASE WHEN type = 'DEBIT' THEN weight ELSE 0 END) AS gold_balance
                ")
                    ->whereNot('is_canceled', true)
                    ->where('category', 'GOLD')->value('gold_balance');
            },
        );
    }

    protected function moneyBalance(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->transactions()->selectRaw("
                    SUM(CASE WHEN type = 'CREDIT' THEN amount ELSE 0 END) -
                    SUM(CASE WHEN type = 'DEBIT' THEN amount ELSE 0 END) AS money_balance
                ")
                    ->whereNot('is_canceled', true)
                    ->where('category', 'MONEY')->value('money_balance');
            },
        );
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
