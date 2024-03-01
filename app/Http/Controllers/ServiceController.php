<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Meta;
use App\Models\Service;
use App\Rules\MaxLines;
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
                ->with('costumer')
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
                ->appends($request->all()),
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
            'paid_amount' => 'required',
            'estimated_date' => 'required',
            'remarks' => ['nullable', new MaxLines(10)],
            'status' => 'required'
        ], [
            'costumer_id.required' => 'Pelanggan wajib diisi.',
            'category_id.required' => 'Kategori wajib diisi.',
            'weight.required' => 'Berat wajib diisi.',
            'cost.required' => 'Biaya wajib diisi.',
            'estimated_date.required' => 'Tanggal estimasi wajib diisi.',
            'status.required' => 'Status wajib diisi.',
            'paid_amount.required' => 'Jumlah bayar wajib diisi.',
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
                'status' => $request->status,
                'updated_by' => auth()->id()
            ]);
        } elseif ($request->type == 'paid-full') {
            $service->update([
                'paid_amount' => $service->cost,
                'updated_by' => auth()->id()
            ]);
        } elseif ($request->type == 'taken') {
            $service->update([
                'date_taken' => now(),
                'updated_by' => auth()->id()
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

    /**
     * Print the specified resource.
     */
    public function print(Service $service)
    {
        $service->load('costumer', 'category');

        $config = Meta::whereIn('key', [
            'invoice_service_header_image', 'invoice_service_paper_size', 'invoice_service_note'
        ])->pluck('value', 'key');

        return inertia('Service/Print', [
            'service' => $service,
            'config' => $config
        ]);
    }
}
