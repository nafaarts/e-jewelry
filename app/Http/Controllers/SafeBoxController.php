<?php

namespace App\Http\Controllers;

use App\Models\SafeBox;
use Illuminate\Http\Request;

class SafeBoxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sorting = getOrderTable($request->order);

        return inertia('SafeBox/Index', [
            'safeboxes' => SafeBox::query()
                ->when($request->input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->orderBy($sorting['column'], $sorting['direction'])
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
        return inertia('SafeBox/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'remarks' => 'nullable'
        ]);

        SafeBox::create($validated);

        return to_route('safeboxes.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SafeBox $safebox)
    {
        return inertia('SafeBox/Edit', [
            'safebox' => $safebox
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SafeBox $safebox)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'remarks' => 'nullable'
        ]);

        $safebox->update($validated);

        return to_route('safeboxes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SafeBox $safebox)
    {
        $safebox->delete();

        return to_route('safeboxes.index');
    }
}
