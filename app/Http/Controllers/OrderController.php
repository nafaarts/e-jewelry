<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Price;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return inertia('Order/Index', [
            'orders' => Order::query()
                ->when($request->input('search'), function ($query, $search) {
                    $query->where('order_number', 'like', "%{$search}%")
                        ->orWhereHas('costumer', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('phone_number', 'like', "%{$search}%")
                                ->orWhere('address', 'like', "%{$search}%");
                        });
                })
                ->latest()
                ->paginate(10)
                ->withQueryString()
                ->through(function ($item) {
                    return [
                        ...$item->toArray(),
                        'costumer' => $item?->costumer?->name,
                    ];
                }),
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $orderCode =  time() . str_pad(Order::latest()->first()?->id + 1, 4, '0', STR_PAD_LEFT);

        return inertia('Order/Create', [
            'prices' => Price::orderBy('sell_price', 'DESC')->get(),
            'sales' => auth()->user()->name,
            'order_code' => $orderCode
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
