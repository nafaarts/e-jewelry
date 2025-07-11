<?php

namespace App\Http\Controllers;

use App\Models\Jewelry;
use App\Models\Meta;
use App\Models\Order;
use App\Models\Sale;
use App\Rules\MaxLines;
use Barryvdh\DomPDF\Facade\Pdf;
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
                ->with('costumer')
                ->when($request->input('search'), function ($query, $search) {
                    $query->where('sale_number', 'like', "%{$search}%")
                        ->orWhereHas('costumer', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('phone_number', 'like', "%{$search}%")
                                ->orWhere('address', 'like', "%{$search}%");
                        });
                })
                ->withCount('items')
                ->latest()
                ->paginate(10)
                ->through(function ($sale) {
                    if ($sale->is_percent_discount) {
                        $sale->discount = $sale->total_amount * $sale->discount / 100;
                    }

                    $sale->total_amount_with_discount = $sale->total_amount - $sale->discount;
                    return $sale;
                })
                ->appends($request->all()),
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $order = Order::find($request->order_id);
        if ($order) {
            $order->load('jewelry', 'costumer');

            if ($order->jewelry->status == 'SOLD') {
                return back()->with('message', 'Barang sudah terjual!');
            }
        }

        return inertia('Sale/Create', [
            'sales' => auth()->user()->name,
            'order' => $order
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'costumer_id' => 'required',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:jewelries,id',
            'discount' => 'nullable|numeric',
            'remarks' => ['nullable', new MaxLines(5)],
        ], [
            'costumer_id.required' => 'Data kostumer wajib diisi.'
        ]);

        $sale_number = generateInvoiceNumber('sale');

        $data = [
            'costumer_id' => $request->costumer_id,
            'sale_number' => $sale_number,
            'discount' => $request->discount,
            'is_percent_discount' => $request->is_percent_discount,
            'total_amount' => $request->total_amount,
            'remarks' => $request->remarks,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ];

        $sale = Sale::create($data);

        foreach ($request->items ?? [] as $item) {
            Jewelry::find($item['id'])->update([
                'safe_box_id' => null,
                'status' => 'SOLD'
            ]);

            $sale->items()->create([
                'jewelry_id' => $item['id'],
                'price' => $item['sell_price']
            ]);
        }

        return to_route('sales.show', $sale);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        $sale->load('costumer', 'createdBy', 'updatedBy');

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
                'sell_price' => $item->jewelry->sell_price,
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


    /**
     * Print the specified resource.
     */
    public function print(Sale $sale)
    {
        $sale->load('items', 'costumer');

        $config = Meta::whereIn('key', [
            'invoice_sale_header_image', 'invoice_sale_paper_size', 'invoice_sale_note'
        ])->pluck('value', 'key');

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
                'sell_price' => $item->jewelry->sell_price,
                'photo' => $item->jewelry->photo
            ];
        });

        return Pdf::loadView('invoice/sale', [
            'sale' => $sale,
            'config' => $config
        ])
            ->setPaper($config['invoice_sale_paper_size'], 'portrait')
            ->stream(env('APP_NAME') . '.pdf');
    }
}
