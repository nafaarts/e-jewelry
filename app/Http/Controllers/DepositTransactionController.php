<?php

namespace App\Http\Controllers;

use App\Models\DepositAccount;
use App\Models\DepositTransaction;
use App\Models\Meta;
use Barryvdh\DomPDF\Facade\Pdf;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepositTransactionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(DepositAccount $deposit_account)
    {
        if (!$deposit_account->is_active) {
            return abort(404);
        }

        return inertia('Transaction/Create', [
            'account' => $deposit_account->load('costumer'),
            'sales' => auth()->user()->name
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, DepositAccount $deposit_account)
    {
        dd($deposit_account?->moneyBalance(), $deposit_account?->goldBalance());
        
        $validated = $request->validate([
            'type' => 'required|in:DEBIT,CREDIT',
            'category' => 'required|in:MONEY,GOLD',
            'weight' => [Rule::requiredIf($request->category == 'GOLD'), function (string $attribute, mixed $value, Closure $fail) use ($request, $deposit_account) {
                if ($request->type == 'DEBIT' && $value > $deposit_account?->goldBalance()) {
                    $fail("Jumlah berat tidak cukup.");
                }
            }],
            'amount' => [Rule::requiredIf($request->category == 'MONEY'),  function (string $attribute, mixed $value, Closure $fail) use ($request, $deposit_account) {
                dd((int) str($value)->replace(',', ''), $value);
                
                if ($request->type == 'DEBIT' && (int) str($value)->replace(',', '') > $deposit_account?->moneyBalance()) {
                    $fail("Jumlah saldo tidak cukup.");
                }
            }],
            'cost' => 'nullable',
            'remarks' => 'nullable|max:255',
        ]);

        $validated['amount'] = str($validated['amount'])->replace(',', '')->toInteger();
        $validated['cost'] = str($validated['cost'])->replace(',', '')->toInteger();

        // generate transaction code
        $transactionUserCount = $deposit_account->transactions()->count();
        $date = now()->format('ymd');
        $lastItem = str_pad($transactionUserCount + 1, 3, '0', STR_PAD_LEFT);
        $randomNumber = rand(1000, 9999);

        $transaction_number = 'TRX/' . $date . '/' . $randomNumber . $lastItem;

        $deposit_account->transactions()->create([
            ...$validated,
            'transaction_number' => $transaction_number,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return to_route('deposits.show', $deposit_account);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, DepositAccount $deposit_account,  DepositTransaction $transaction)
    {
        return inertia('Transaction/Detail', [
            'account' => $deposit_account->with('costumer', 'createdBy')->withCount('transactions')->first(),
            'transaction' => $transaction->load('createdBy'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DepositAccount $deposit_account, DepositTransaction $transaction)
    {
        // transaction can't be deleted, canceled instead.
        $transaction->update([
            'is_canceled' => true
        ]);

        return to_route('deposits.transactions.show', [$deposit_account, $transaction]);
    }

    public function print(DepositAccount $deposit_account, DepositTransaction $transaction)
    {
        $config = Meta::whereIn('key', [
            'invoice_deposit_header_image', 'invoice_deposit_paper_size', 'invoice_deposit_note'
        ])->pluck('value', 'key');

        return Pdf::loadView('invoice/deposit', [
            'transaction' => $transaction,
            'account' => $deposit_account,
            'config' => $config
        ])
            ->setPaper($config['invoice_deposit_paper_size'], 'portrait')
            ->stream(env('APP_NAME') . '.pdf');
    }
}
