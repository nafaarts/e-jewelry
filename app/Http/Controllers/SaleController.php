<?php

namespace App\Http\Controllers;

use App\Models\Jewelry;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return inertia('Sale/Index', [
            'sales' => Sale::query()
                ->when($request->input('search'), function ($query, $search) {
                    $query->where('sale_number', 'like', "%{$search}%")
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
                        'total_items' => $item->items->count()
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
        return inertia('Sale/Create', [
            'sales' => auth()->user()->name,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'costumer_id' => 'required',
        ]);

        $sale_number = time() . str_pad(Sale::latest()->first()?->id + 1, 4, '0', STR_PAD_LEFT);

        $sale = Sale::create([
            'costumer_id' => $request->costumer_id,
            'sale_number' => $sale_number,
            'total_amount' => $request->total_amount,
            'remarks' => $request->remarks,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        foreach ($request->items ?? [] as $item) {
            Jewelry::find($item['id'])->update([
                'status' => 'SOLD'
            ]);

            $sale->items()->create([
                'jewelry_id' => $item['id'],
                'price' => $item['sellPrice']
            ]);
        }

        return to_route('sales.show', $sale);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        $sale->load('costumer');
        $sale->load('createdBy');
        $sale->load('updatedBy');

        $sale->sold_items = $sale->items->map(function ($item) {
            return [
                'id' => $item->jewelry->id,
                'jewelry_code' => $item->jewelry->jewelry_code,
                'name' => $item->jewelry->name,
                'remarks' => $item->jewelry->remarks ?? '-',
                'weight' => $item->jewelry->weight,
                'price' => [
                    'carat' => $item->jewelry->price->carat,
                    'rate' => $item->jewelry->price->rate,
                ],
                'sellPrice' => $item->jewelry->sellPrice(),
                'photo' => $item->jewelry->photo
            ];
        });

        return inertia('Sale/Detail', [
            'sale' => $sale
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        foreach ($sale->items as $item) {
            Jewelry::find($item['jewelry_id'])->update([
                'status' => 'READY'
            ]);
        }

        $sale->delete();

        return to_route('sales.index');
    }
}
