<?php

namespace App\Http\Controllers;

use App\Models\Price;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Inertia\Response
     */
    public function index()
    {
        $prices = Price::get(['id', 'name', 'carat', 'rate']);

        $prices->prepend([
            'id' => 'all',
            'name' => 'Semua Harga dan Kadar'
        ]);

        return inertia('Report/Index', compact('prices'));
    }

    /**
     * Show the result of the report.
     */
    public function result(Request $request)
    {
        $priceId = $request->input('price');
        $from = $request->input('from');
        $to = $request->input('to');

        $soldItems = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('jewelries', 'sale_items.jewelry_id', '=', 'jewelries.id')
            ->when($priceId !== 'all', function ($query) use ($priceId) {
                $query->where('price_id', $priceId);
            })
            ->whereBetween('sales.created_at', [$from, $to])
            ->select(
                'sales.created_at as date',
                'jewelries.jewelry_code as jewelry_number',
                'jewelries.name as jewelry_name',
                'jewelries.weight as weight',
                'jewelries.price_id as price_id',
            )
            ->get()
            ->groupBy('price_id');

        $prices = Price::when($priceId !== 'all', function ($query) use ($priceId) {
            $query->where('id', $priceId);
        })
            ->select('id', 'name', 'carat', 'rate')
            ->get();

        $prices->each(function ($price) use ($soldItems) {
            $price->soldItems = $soldItems->get($price->id);
        });

        return inertia('Report/Result', [
            'prices' => $prices,
            'from' => $from,
            'to' => $to,
        ]);
    }
}
