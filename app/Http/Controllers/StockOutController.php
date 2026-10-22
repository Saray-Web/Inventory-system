<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOutController extends Controller
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
        $products = Product::where('is_active', true)
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
            'note' => 'nullable|string',
        ]);

        $referenceNo = DB::transaction(function () use ($request) {

            $product = Product::whereKey($request->product_id)
                ->lockForUpdate()
                ->firstOrFail();

            // Check stock before stock out
            if ($request->quantity > $product->quantity) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'quantity' => 'Stock មិនគ្រប់គ្រាន់។ Available stock: '
                        . $product->quantity,
                ]);
            }

            $referenceNo = $this->generateReferenceNo();

            // Create stock out record
            StockOut::create([
                'reference_no' => $referenceNo,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'note' => $request->note,
            ]);

            // Decrease product quantity
            $product->decrement('quantity', $request->quantity);

            return $referenceNo;
        });

        return redirect()
            ->route('stockOut.index')
            ->with(
                'success',
                'Stock Out created successfully with Ref: ' . $referenceNo
            );
    }
    
    private function generateReferenceNo()
    {
        $referenceDate = now();
        $date = $referenceDate->toDateString();

        DB::table('reference_sequences')->insertOrIgnore([
            'type' => 'SO',
            'reference_date' => $date,
            'last_value' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $sequence = DB::table('reference_sequences')
            ->where('type', 'SO')
            ->where('reference_date', $date)
            ->lockForUpdate()
            ->first();

        $latestReference = StockOut::where('reference_no', 'like', 'SO-' . $referenceDate->format('Ymd') . '-%')
            ->orderByDesc('reference_no')
            ->value('reference_no');
        $latestValue = $latestReference ? (int) substr($latestReference, strrpos($latestReference, '-') + 1) : 0;
        $nextValue = max($sequence->last_value, $latestValue) + 1;

        DB::table('reference_sequences')
            ->where('id', $sequence->id)
            ->update([
                'last_value' => $nextValue,
                'updated_at' => now(),
            ]);

        return 'SO-' . $referenceDate->format('Ymd') . '-' . str_pad($nextValue, 4, '0', STR_PAD_LEFT);
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
