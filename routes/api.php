<?php

use App\Models\Costumer;
use App\Models\Jewelry;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/costumer', function (Request $request) {
        return $request->input('search') ? Costumer::query()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('costumer_code', 'like', "%{$search}%")
                    ->orWhere('indentity_number', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            })
            ->latest()
            ->limit(3)
            ->get() : [];
    });

    Route::post('/costumer/store', function (Request $request) {
        $validated = $request->validate([
            'name' => 'required',
            'phone_number' => 'required|numeric|unique:' . Costumer::class,
            'address' => 'required'
        ]);

        $validated['costumer_code'] = time() . str_pad(Costumer::latest()->first()?->id + 1, 4, '0', STR_PAD_LEFT);

        return Costumer::create($validated);
    });

    Route::get('/jewelry', function (Request $request) {
        $jewelry = Jewelry::where('jewelry_code', $request->code)->where('status', 'READY')->first();

        if (!$jewelry) {
            return response()->json([
                'message' => 'Not Found',
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => [
                'id' => $jewelry->id,
                'jewelry_code' => $jewelry->jewelry_code,
                'name' => $jewelry->name,
                'weight' => $jewelry->weight,
                'price' => [
                    'category' => $jewelry->price->category,
                    'carat' => $jewelry->price->carat,
                    'rate' => $jewelry->price->rate,
                ],
                'sell_price' => $jewelry->sell_price,
                'photo' => $jewelry->photo
            ]
        ]);
    });
});
