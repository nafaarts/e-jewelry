<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return inertia('Supplier/Index', [
            'suppliers' => Supplier::query()
                ->when($request->input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('supplier_code', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                })
                ->latest()
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
        $supplierCode =  generateItemNumber('supplier');
        return inertia('Supplier/Create', [
            'supplier_code' => $supplierCode
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_code' => 'required',
            'name' => 'required|max:255',
            'email' => 'nullable|email|max:255|unique:suppliers,email',
            'phone_number' => 'required|numeric|min:10',
            'address' => 'required|max:255',
            'photo' => 'nullable|image|max:5240',
            'remarks' => 'nullable|max:255'
        ]);

        if ($validated['photo']) {
            $request->file('photo')->store('public/supplier');
            $validated['photo'] = $request->file('photo')->hashName('supplier');
        }

        Supplier::create($validated);

        return to_route('suppliers.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return inertia('Supplier/Edit', [
            'supplier' => $supplier
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'supplier_code' => 'required',
            'name' => 'required|max:255',
            'email' => ['nullable', 'email', 'max:255', Rule::unique(Supplier::class)->ignore($supplier->id)],
            'phone_number' => 'required|numeric|min:10',
            'address' => 'required|max:255',
            'photo' => 'nullable|image|max:5240',
            'remarks' => 'nullable|max:255'
        ]);

        if ($validated['photo']) {
            if ($supplier->photo) unlink('storage/' . $supplier->photo);
            $request->file('photo')->store('public/supplier');
            $validated['photo'] = $request->file('photo')->hashName('supplier');
        } else {
            $validated['photo'] = $supplier->photo;
        }

        $supplier->update($validated);

        return to_route('suppliers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        if ($supplier->photo) unlink('storage/' . $supplier->photo);
        $supplier->delete();

        return to_route('suppliers.index');
    }
}
