<?php

namespace App\Http\Controllers;

use App\Models\Price;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return inertia('Price/Index', [
            'prices' => Price::query()
                ->when($request->input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('carat', 'like', "%{$search}%")
                        ->orWhere('rate', 'like', "%{$search}%")
                        ->orWhere('weight', 'like', "%{$search}%")
                        ->orWhere('sell_price', 'like', "%{$search}%")
                        ->orWhere('buy_price', 'like', "%{$search}%")
                        ->orWhere('cost', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhere('remarks', 'like', "%{$search}%")
                        ->orWhere('created_at', 'like', "%{$search}%");
                })
                ->withCount('jewelries')
                ->orderBy('sell_price', 'DESC')
                ->paginate(10)
                ->withQueryString(),
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Price/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'carat' => 'required',
            'rate' => 'required',
            'weight' => 'required',
            'sell_price' => 'required',
            'buy_price' => 'required',
            'cost' => 'nullable',
            'category' => 'required',
            'remarks' => 'nullable|max:255',
        ]);

        $validated['sell_price'] = str($validated['sell_price'])->replace(',', '')->toInteger();
        $validated['buy_price'] = str($validated['buy_price'])->replace(',', '')->toInteger();
        $validated['cost'] = str($validated['cost'])->replace(',', '')->toInteger();

        Price::create($validated);

        return to_route('prices.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Price $price)
    {
        return inertia('Price/Edit', [
            'price' => $price
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Price $price)
    {
        $validated = $request->validate([
            'name' => 'required',
            'carat' => 'required',
            'rate' => 'required',
            'weight' => 'required',
            'sell_price' => 'required',
            'buy_price' => 'required',
            'cost' => 'nullable',
            'category' => 'required',
            'remarks' => 'nullable|max:255',
        ]);

        $validated['sell_price'] = str($validated['sell_price'])->replace(',', '')->toInteger();
        $validated['buy_price'] = str($validated['buy_price'])->replace(',', '')->toInteger();
        $validated['cost'] = str($validated['cost'])->replace(',', '')->toInteger();

        $price->update($validated);

        return to_route('prices.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Price $price)
    {
        $price->delete();

        return to_route('prices.index');
    }
}
