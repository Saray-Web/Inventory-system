<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $totalProducts = Product::count();

        $query = Product::with('category');

        if($request->filled('category')){
            
            $query->where('category_id', $request->category);
        }
        $products = $query->latest()->paginate(5)->withQueryString();
        return view('products.index', compact('products', 'totalProducts', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120|dimensions:min_width=600,min_height=600',
        ]);

        $imageUrl = null;
        $publicId = null;
        if ($request->hasFile('image')) {
            $upload = Cloudinary::uploadApi()->upload(
                $request->file('image')->getRealPath(),
                [
                    'folder' => 'inventory system'
                ]
            );
            $imageUrl = $upload['secure_url'];
            $publicId = $upload['public_id'];
        }

        DB::transaction(function () use ($request, $imageUrl, $publicId) {
            Product::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'sku' => $this->generateSku($request->name),
                'price' => $request->price,
                'quantity' => 0,
                'minimum_stock' => $request->minimum_stock,
                'description' => $request->description,
                'image' => $imageUrl,
                'image_public_id' => $publicId,
            ]);
        });
        return redirect()->route('product.index')->with('success', 'Created products success');
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
    public function edit(product $product)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('products.edit', compact('categories', 'product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120|dimensions:min_width=600,min_height=600',
        ]);

        $imageUrl = $product->image;
        $publicId = $product->image_public_id;

        if ($request->hasFile('image')) {
            // delete old image
            if ($product->image_public_id) {
                Cloudinary::uploadApi()->destroy($product->image_public_id);
            }

            // for upload new image
            $upload = Cloudinary::uploadApi()->upload(
                $request->file('image')->getRealPath(),
                [
                    'folder' => 'inventory system'
                ]
            );
            $imageUrl = $upload['secure_url'];
            $publicId = $upload['public_id'];
        }

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'minimum_stock' => $request->minimum_stock,
            'description' => $request->description,
            'image' => $imageUrl,
            'image_public_id' => $publicId,

        ]);
        return redirect()->route('product.index')->with('success', 'Updated product successfull');
    }

    private function generateSku(string $name): string
    {
        $letters = preg_replace('/[^A-Za-z0-9]/', '', Str::ascii($name));
        $prefix = Str::upper(Str::substr($letters, 0, 2));
        $prefix = strlen($prefix) === 2 ? $prefix : 'PR';

        DB::table('product_sku_sequences')->insertOrIgnore([
            'prefix' => $prefix,
            'last_value' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $sequence = DB::table('product_sku_sequences')
            ->where('prefix', $prefix)
            ->lockForUpdate()
            ->first();

        $latestSku = Product::where('sku', 'like', $prefix . '-%')
            ->orderByDesc('sku')
            ->value('sku');
        $latestValue = $latestSku ? (int) substr($latestSku, strrpos($latestSku, '-') + 1) : 0;
        $nextValue = max($sequence->last_value, $latestValue) + 1;

        DB::table('product_sku_sequences')
            ->where('id', $sequence->id)
            ->update([
                'last_value' => $nextValue,
                'updated_at' => now(),
            ]);

        return $prefix . '-' . str_pad($nextValue, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $hasStockHistory =
            // $product->stockIns()->exists() ||
            // $product->stockOuts()->exists();
            $product->stockIn()->exists() || $product->stockOut()->exists();

        // Product មាន history → Deactivate only
        if ($hasStockHistory) {

            $product->update([
                'is_active' => false,
            ]);

            return redirect()
                ->route('product.index')
                ->with('success', 'Product deactivated successfully.');
        }

        // Product គ្មាន history → Delete image
        if ($product->image_public_id) {
            Cloudinary::uploadApi()->destroy(
                $product->image_public_id
            );
        }

        $product->delete();

        return redirect()
            ->route('product.index')
            ->with('success', 'Product deleted successfully.');
    }
}
