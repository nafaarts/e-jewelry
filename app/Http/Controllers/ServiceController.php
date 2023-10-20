<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return inertia('Service/Index', [
            'services' => Service::query()
                ->when($request->input('search'), function ($query, $search) {
                    $query->where('service_number', 'like', "%{$search}%")
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
                        'costumer' => $item?->costumer?->name
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
        return inertia('Service/Create', [
            'categories' => Category::all(),
            'sales' => auth()->user()->name,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'costumer_id' => 'required',
            'category_id' => 'required',
            'weight' => 'required',
            'cost' => 'required',
            'paid_amount' => 'nullable',
            'remarks' => 'nullable',
            'status' => 'required'
        ]);

        $serviceNumber = time() . str_pad(Service::latest()->first()?->id + 1, 4, '0', STR_PAD_LEFT);

        $validated['cost'] = str($validated['cost'])->replace(',', '')->toInteger();

        $validated['paid_amount'] = str($validated['paid_amount'])->replace(',', '')->toInteger();

        $service = Service::create([
            ...$validated,
            'service_number' => $serviceNumber,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return to_route('services.show', $service);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        $service->load('costumer');
        $service->load('category');
        $service->load('createdBy');
        $service->load('updatedBy');

        return inertia('Service/Detail', [
            'service' => $service
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        if ($request->type == 'status') {
            $service->update([
                'status' => $request->status
            ]);
        } elseif ($request->type == 'paid-full') {
            $service->update([
                'paid_amount' => $service->cost
            ]);
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();
        return to_route('services.index');
    }
}
