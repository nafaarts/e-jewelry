<?php

namespace App\Http\Controllers;

use App\Models\Costumer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CostumerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return inertia('Costumer/Index', [
            'costumers' => Costumer::query()
                ->when($request->input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('costumer_code', 'like', "%{$search}%")
                        ->orWhere('indentity_number', 'like', "%{$search}%")
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
        $costumerCode =  time() . str_pad(Costumer::latest()->first()?->id + 1, 4, '0', STR_PAD_LEFT);
        return inertia('Costumer/Create', [
            'costumer_code' => $costumerCode
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'costumer_code' => 'required',
            'name' => 'required|max:255',
            'indentity_number' => 'nullable|max:255|unique:costumers,indentity_number',
            'phone_number' => 'required|numeric|min:10',
            'address' => 'required|max:255',
            'photo' => 'nullable|image|max:5240',
            'remarks' => 'nullable|max:255'
        ]);

        if ($validated['photo']) {
            $request->file('photo')->store('public/costumer');
            $validated['photo'] = $request->file('photo')->hashName('costumer');
        }

        Costumer::create($validated);

        return to_route('costumers.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Costumer $costumer)
    {
        return inertia('Costumer/Edit', [
            'costumer' => $costumer
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Costumer $costumer)
    {
        $validated = $request->validate([
            'costumer_code' => 'required',
            'name' => 'required|max:255',
            'indentity_number' => ['nullable', 'max:255', Rule::unique(Costumer::class)->ignore($costumer->id)],
            'phone_number' => 'required|numeric|min:10',
            'address' => 'required|max:255',
            'photo' => 'nullable|image|max:5240',
            'remarks' => 'nullable|max:255'
        ]);

        if ($validated['photo']) {
            if ($costumer->photo) unlink('storage/' . $costumer->photo);
            $request->file('photo')->store('public/costumer');
            $validated['photo'] = $request->file('photo')->hashName('costumer');
        } else {
            $validated['photo'] = $costumer->photo;
        }

        $costumer->update($validated);

        return to_route('costumers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Costumer $costumer)
    {
        if ($costumer->photo) unlink('storage/' . $costumer->photo);
        $costumer->delete();

        return to_route('costumers.index');
    }
}
