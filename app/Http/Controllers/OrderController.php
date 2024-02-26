<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Meta;
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
                ->with('costumer')
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
                ->appends($request->all()),
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Order/Create', [
            'categories' => Category::all(),
            'prices' => Price::orderBy('sell_price', 'DESC')->get(),
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
            'price_id' => 'required',
            'category_id' => 'required',
            'weight' => 'required',
            'cost' => 'nullable',
            'total_price' => 'required',
            'paid_amount' => 'required',
            'status' => 'required',
            'remarks' => 'nullable|max:255',
        ], [
            'costumer_id.required' => 'Data kostumer wajib diisi.',
            'category_id.required' => 'Kategori wajib diisi.',
            'price_id.required' => 'Kadar dan Harga wajib diisi.'
        ]);

        $orderCode =  time() . str_pad(Order::latest()->first()?->id + 1, 4, '0', STR_PAD_LEFT);

        $data = [
            'costumer_id' => $request->costumer_id,
            'category_id' => $request->category_id,
            'order_number' => $orderCode,
            'weight' => $request->weight,
            'cost' => str($request->cost)->replace(',', '')->toInteger(),
            'saved_price' => Price::findOrFail($request->price_id)->toJson(),
            'total_price' => str($request->total_price)->replace(',', '')->toInteger(),
            'paid_amount' => str($request->paid_amount)->replace(',', '')->toInteger(),
            'status' => $request->status,
            'remarks' => $request->remarks,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id()
        ];

        Order::create($data);

        return to_route('orders.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load('costumer');
        $order->load('jewelry');
        $order->load('category');
        $order->load('createdBy');
        $order->load('updatedBy');

        $order->saved_price = json_decode($order->saved_price);

        return inertia('Order/Detail', [
            'order' => $order
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        if ($request->type == 'status') {
            $order->update([
                'status' => $request->status,
                'updated_by' => auth()->id()
            ]);
        } elseif ($request->type == 'paid-full') {
            $order->update([
                'paid_amount' => $order->total_price,
                'updated_by' => auth()->id()
            ]);
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return to_route('orders.index');
    }

    public function print(Order $order)
    {
        $order->load('costumer', 'jewelry', 'category');

        $order->saved_price = json_decode($order->saved_price);

        $config = Meta::whereIn('key', [
            'invoice_order_header_image', 'invoice_order_paper_size', 'invoice_order_note'
        ])->pluck('value', 'key');

        return inertia('Order/Print', [
            'order' => $order,
            'config' => $config
        ]);
    }
}
