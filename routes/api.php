<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// class Price
// {
//     public int $sell;
//     public int $neutral;
//     public int $buy;
//     public string $created_at;
//     public string $updated_at;
// }

// Route::get('/prices', function () {
//     $period = CarbonPeriod::create(now()->parse('1 year ago'), now());

//     $result = collect([]);
//     $lastIndex = 800000;
//     $growingValue = 10000;
//     foreach ($period as $key => $date) {
//         $data = new Price();
//         $data->created_at = $date->format('Y-m-d H:i:s');
//         $data->updated_at = $date->format('Y-m-d H:i:s');

//         if ($key > 100 && $key < 140) {
//             $lastIndex -= 1500;
//             $growingValue = 2500;
//         };
//         $sellPrice = random_int($lastIndex, $lastIndex += $growingValue);

//         $buyPrice = $sellPrice - ($sellPrice * 11 / 100);
//         $neutralPrice = ($buyPrice + $sellPrice) / 2;

//         $data->sell = $sellPrice;
//         $data->buy = $buyPrice;
//         $data->neutral = $neutralPrice;

//         $result->push($data);
//     }

//     return $result->whereBetween('created_at', [now()->parse(request('filter') ?? '1 week ago'), now()])->values()->map(function ($item) {
//         $item->label = now()->create($item->created_at)->format('d-m-y');
//         return $item;
//     });
// });