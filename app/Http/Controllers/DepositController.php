<?php

namespace App\Http\Controllers;

use App\Models\DepositAccount;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sorting = getOrderTable($request->order);

        return inertia('Deposit/Index', [
            'accounts' => DepositAccount::query()
                ->with('costumer')
                ->when($request->input('search'), function ($query, $search) {
                    $query->where('account_number', 'like', "%{$search}%")
                        ->orWhereHas('costumer', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('phone_number', 'like', "%{$search}%")
                                ->orWhere('address', 'like', "%{$search}%");
                        });
                })
                ->withCount('transactions')
                ->orderBy($sorting['column'], $sorting['direction'])
                ->paginate(10)
                ->appends($request->all()),
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Deposit/Create', [
            'sales' => auth()->user()->name,
            'account_number' => generateItemNumber('deposit-account'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'costumer_id' => 'required|exists:costumers,id',
            'is_active' => 'required',
            'remarks' => 'nullable',
        ], [
            'costumer_id.required' => 'Data kastamer tidak boleh kosong',
            'costumer_id.exists' => 'Data kastamer tidak ditemukan',
            'account_number.required' => 'Nomor rekening tidak boleh kosong',
            'is_active.required' => 'Status rekening tidak boleh kosong',
        ]);

        $account_number = generateItemNumber('deposit-account');

        $account = DepositAccount::create([
            'costumer_id' => $request->costumer_id,
            'account_number' => $account_number,
            'is_active' => $request->is_active,
            'remarks' => $request->remarks,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return to_route('deposits.show', $account);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, DepositAccount $deposit_account)
    {
        return inertia('Deposit/Detail', [
            'account' => $deposit_account->load('costumer', 'createdBy')->loadCount('transactions'),
            'transactions' => $deposit_account->transactions()->when($request->search, function ($query, $value) {
                return $query->where('transaction_number', 'LIKE', "%$value%");
            })->latest()->paginate(12)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DepositAccount $account)
    {
        $account->update([
            'is_active' => !$account->is_active
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DepositAccount $account)
    {
        if ($account->transactions()->count() == 0) {
            $account->delete();
        }

        return to_route('deposits.index');
    }
}
