<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // $categories = category::latest()->get();
        $totalTransactions = StockOut::count() + StockIn::count();
        $Total_Stock_In_Transactions = StockIn::count();
        $Total_Stock_Out_Transactions = StockOut::count();

        return view('history.index', compact(
            'totalTransactions',
            'Total_Stock_Out_Transactions',
            'Total_Stock_In_Transactions'
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
