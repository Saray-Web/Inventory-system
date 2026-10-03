<?php

namespace App\Http\Controllers;

use App\Models\product;
use App\Models\stockOut;
use Illuminate\Http\Request;

class stockOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $stockOuts = StockOut::with(['product', 'product.category'])
            ->when($search, function ($query, $search) {
                $query->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(5) -> withQueryString();
            // ->withQueryString();

        $totalIssued = StockOut::sum('quantity');
        $totalTransactions = StockOut::count();
        $latestActivity = StockOut::with('product')->latest()->first();

        return view('stock-out.index', compact(
            'stockOuts',
            'totalIssued',
            'totalTransactions',
            'latestActivity'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = product::where('is_active', true)
            ->where('quantity', '>', 0)
            ->orderBy('name')
            ->get();

        return view('stock-out.create', compact('products'));
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

        $product = product::findOrFail($request->product_id);
        // Check stock before stock out
        if ($request->quantity > $product->quantity) {
            return back()
                ->withInput()
                ->withErrors([
                    'quantity' => 'Stock មិនគ្រប់គ្រាន់។ Available stock: ' . $product->quantity,
                ]);
        }
        $referenceNo = $this->generateReferenceNo();
        // Create stock out record
        stockOut::create([
            'reference_no' => $referenceNo,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'note' => $request->note,
        ]);

        // Decrease product quantity
        $product->decrement('quantity', $request->quantity);

        return redirect()
            ->route('stockOut.index')
            ->with('success', 'Stock Out created successfully with Ref: ' . $referenceNo);
    }

    private function generateReferenceNo()
    {
        $date = now()->format('Ymd');
        $prefix = 'SO-' . $date . '-';

        $latest = stockOut::where('reference_no', 'LIKE', $prefix . '%')
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
