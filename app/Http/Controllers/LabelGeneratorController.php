<?php

namespace App\Http\Controllers;

use App\Models\Jewelry;
use Illuminate\Http\Request;

class LabelGeneratorController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $sorting = getOrderTable($request->order, 'jewelry_code');

        return inertia('LabelGenerator/Index', [
            'jewelries' => Jewelry::query()
                ->with('price', 'category')
                ->when($request->input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('jewelry_code', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                })
                ->orderBy($sorting['column'], $sorting['direction'])
                ->paginate(10)
                ->appends($request->all()),
            'filters' => $request->only(['search', 'order']),
        ]);
    }
}
