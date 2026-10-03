<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use Illuminate\Http\Request;

class StockInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stockIns = StockIn::with('product')->latest()->paginate(5);

        $latestStockIn = StockIn::with('product')->get()->first();
        return view('stock-in.index', compact('stockIns', 'latestStockIn'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('stock-in.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string'
        ]);

        $referenceNo = $this->generateReferenceNo();
        
        StockIn::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'note' => $request->note,
            'reference_no' => $referenceNo,
        ]);
        $product = Product::findOrFail($request->product_id);
        $product->increment('quantity', $request->quantity);
        return redirect()->route('stockIn.index')->with('success', 'Created stock successfull');
    }
    
    private function generateReferenceNo()
    {
        $date = now()->format('Ymd');
        $prefix = 'SI-' . $date . '-';

        $latest = StockIn::where('reference_no', 'LIKE', $prefix . '%')
            ->latest('id')
            ->first();

        if (!$latest) {
            return $prefix . '0001';
        }

        $sequence = (int) substr($latest->reference_no, -4);
        $nextSequence = str_pad($sequence + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $nextSequence;
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
