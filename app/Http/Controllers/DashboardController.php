<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalCategories = Category::count();
        $totalProducts = Product::count();
        $totalStockIn = StockIn::sum('quantity');
        $totalStockOut = StockOut::sum('quantity');
        $CurrentStock = Product::sum('quantity');
        $loweStock = Product::whereColumn('quantity', '<=', 'minimum_stock')->count();
        return view('Dashboards.index', compact(
            'totalCategories',
            'totalProducts',
            'CurrentStock',
            'totalStockOut',
            'totalStockIn',
            'loweStock',
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
