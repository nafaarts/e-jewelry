<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Jewelry;
use App\Models\Order;
use App\Models\Price;
use App\Models\SafeBox;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class JewelryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sorting = getOrderTable($request->order, 'jewelry_code');

        return inertia('Jewelry/Index', [
            'jewelries' => Jewelry::query()
                ->with('price', 'category')
                ->when($request->input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('jewelry_code', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                })
                ->orderBy($sorting['column'], $sorting['direction'])
                ->paginate(12)
                ->appends($request->all()),
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $jewelryCode = generateItemNumber('jewelry');

        $order = Order::find($request->order_id);
        if ($order) {
            $order->saved_price = json_decode($order->saved_price);
        }

        return inertia('Jewelry/Create', [
            'prices' => Price::orderBy('sell_price', 'DESC')->get(),
            'categories' => Category::all(),
            'suppliers' => Supplier::all(),
            'safeboxes' => SafeBox::all(),
            'jewelry_code' => $jewelryCode,
            'order' => $order
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'price_id' => 'required',
            'category_id' => 'required',
            'order_id' => 'nullable',
            'supplier_id' => 'required',
            'safe_box_id' => 'required',
            'name' => 'required|max:255',
            'jewelry_code' => 'required|unique:jewelries,jewelry_code',
            'weight' => 'required',
            'cost' => 'nullable',
            'is_percent_cost' => 'required|boolean',
            'photo' => 'nullable|image|max:5240',
            'status' => 'required',
            'remarks' => 'nullable'
        ]);

        $validated['cost'] = str($validated['cost'])->replace(',', '')->toInteger();

        if ($validated['photo']) {
            $request->file('photo')->store('public/jewelry');
            $validated['photo'] = $request->file('photo')->hashName('jewelry');
        }

        $jewelry = Jewelry::create($validated);

        if ($validated['order_id']) {
            $order = Order::findOrFail($validated['order_id']);
            $order->update([
                'jewelry_id' => $jewelry->id,
                'status' => 'SELESAI'
            ]);

            return to_route('orders.show', $validated['order_id']);
        }

        return to_route('jewelries.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jewelry $jewelry)
    {
        return inertia('Jewelry/Edit', [
            'prices' => Price::all(),
            'categories' => Category::all(),
            'suppliers' => Supplier::all(),
            'safeboxes' => SafeBox::all(),
            'jewelry' => $jewelry
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jewelry $jewelry)
    {
        $validated = $request->validate([
            'price_id' => 'required',
            'category_id' => 'required',
            'supplier_id' => 'required',
            'safe_box_id' => 'required',
            'name' => 'required|max:255',
            'jewelry_code' => ['required', Rule::unique(Jewelry::class)->ignore($jewelry->id)],
            'weight' => 'required',
            'cost' => 'nullable',
            'is_percent_cost' => 'required|boolean',
            'photo' => 'nullable|image|max:5240',
            'status' => 'required',
            'remarks' => 'nullable'
        ]);

        $validated['cost'] = str($validated['cost'])->replace(',', '')->toInteger();

        if ($validated['photo']) {
            if ($jewelry->photo) {
                Storage::delete('public/' . $jewelry->photo);
            }
            $request->file('photo')->store('public/jewelry');
            $validated['photo'] = $request->file('photo')->hashName('jewelry');
        } else {
            $validated['photo'] = $jewelry->photo;
        }

        $jewelry->fill($validated);
        $jewelry->save();

        return to_route('jewelries.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jewelry $jewelry)
    {
        if ($jewelry->photo) {
            Storage::delete('public/' . $jewelry->photo);
        }
        $jewelry->delete();

        return to_route('jewelries.index');
    }
}
