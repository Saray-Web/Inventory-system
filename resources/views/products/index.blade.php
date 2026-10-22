@extends('Layouts.app')
@section('content')
    @if (session('success'))
        <div
            class="mb-5 flex items-center gap-3
                rounded-lg border border-green-200
                bg-green-50 px-4 py-3 text-sm text-green-700">

            <i data-lucide="circle-check" class="w-5 h-5"></i>

            <span>
                {{ session('success') }}
            </span>

        </div>
    @endif
    <div>

        {{-- ===== Header row: title + primary action ===== --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Products</h2>
                <p class="text-sm text-slate-500 mt-0.5">{{ $totalProducts }} items in your catalog</p>
            </div>

            <a href="{{ route('product.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700
                       text-white text-sm font-bold shadow-sm shadow-brand-600/20 transition">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Add Product
            </a>
        </div>

        {{-- ===== Filter / toolbar card ===== --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-5">
            <form action="{{ route('product.index') }}" method="GET" class="flex flex-wrap items-center gap-3 p-4">
                <label class="relative flex-1 min-w-[220px]">
                    <i data-lucide="search" class="w-4 h-4 absolute inset-y-0 left-3 my-auto text-slate-400"></i>
                    <input type="search" placeholder="Search by name or SKU..."
                        class="w-full pl-9 pr-3 py-2 text-sm rounded-lg bg-slate-100 border border-transparent
                              focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none transition">
                </label>
                <select name="category" onchange="this.form.submit()"
                    class="text-sm rounded-lg border border-slate-200 bg-white px-3 py-2 text-slate-600
                           focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>

                <select
                    class="text-sm rounded-lg border border-slate-200 bg-white px-3 py-2 text-slate-600
                           focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none">
                    <option value="">Stock Status</option>
                    <option value="in_stock">In Stock</option>
                    <option value="low_stock">Low Stock</option>
                    <option value="out_of_stock">Out of Stock</option>
                </select>

                <button
                    class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-slate-600
                           border border-slate-200 rounded-lg hover:bg-slate-50 transition">
                    <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
                    Filters
                </button>
            </form>
        </div>

        {{-- ===== Product table ===== --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[700px] ">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-sm tracking-wide border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3 font-medium">#</th>
                            <th class="px-6 py-3 font-medium">Image</th>
                            <th class="px-6 py-3 font-medium">Product</th>
                            <th class="px-6 py-3 font-medium">SKU</th>
                            <th class="px-6 py-3 font-medium">Category</th>
                            <th class="px-7 py-3 font-medium ">Stock</th>
                            <th class="px-6 py-3 font-medium ">Price</th>
                            <th class="px-6 py-3 font-medium ">status</th>
                            <th class="px-6 py-3 font-medium item-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">

                        {{-- Row 1 --}}
                        @forelse ($products as $product)
                            <tr class="transition-colors duration-200 lg:hover:bg-green-300/10">

                                <td class="px-6 py-3 font-medium font-mono ">
                                    {{ $products->firstItem() + $loop->index }}
                                </td>
                                <td class="px-6 py-3 font-medium font-mono ">
                                    @if ($product->image)
                                        <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                            class="w-10 h-10 rounded-lg object-cover border border-slate-200">
                                    @else
                                        <div
                                            class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400">
                                            <i data-lucide="image" class="w-5 h-5"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-3 font-medium font-mono ">
                                    {{ $product->name }}
                                </td>
                                <td class="px-6 py-3 text-slate-500 font-mono text-xs">
                                    {{ $product->sku }}
                                </td>
                                <td class="px-6 py-3 text-slate-600">
                                    {{ $product->category->name }}
                                </td>
                                <td class="px-6 py-3 text-slate-700 font-medium">
                                    <div>

                                        @if ($product->quantity == 0)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full
                text-xs font-medium bg-red-100 text-red-700">
                                                Out of Stock
                                            </span>
                                        @elseif ($product->quantity <= $product->minimum_stock)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full
                text-xs font-medium bg-yellow-100 text-yellow-700">
                                                Low Stock
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full
                text-xs font-medium bg-green-100 text-green-700">
                                                In Stock
                                            </span>
                                        @endif

                                        <p class="mt-1 text-sm font-semibold px-2.5">
                                            {{ $product->quantity }}
                                        </p>

                                    </div>
                                </td>
                                <td class="px-6 py-3  text-slate-700">
                                    $ {{ $product->price }}
                                </td>
                                <td class="px-6 py-3 text-slate-700">
                                    @if ($product->is_active)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full
                                        text-xs font-medium bg-green-100 text-green-700">
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full
                                        text-xs font-medium bg-slate-100 text-slate-600">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-3 ">
                                    <div class="flex   gap-1">
                                        <a href="{{ route('product.edit', $product) }}"
                                            class="inline-flex items-center justify-center w-9 h-9 border border-gray-200 text-blue-700 rounded-md
                                                hover:bg-blue-100 transition">
                                            <i data-lucide="SquarePen" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('product.destroy', $product) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to remove this product?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center w-9 h-9
                                                    border border-amber-100 text-amber-600 rounded-md
                                                    hover:bg-amber-50 transition">
                                                <i data-lucide="archive" class="w-4 h-4"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-8 text-center text-slate-400">
                                    No products found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="flex border-t border-slate-200 px-6 py-4 justify-between">
                <div>
                    <p> Page {{ $products->currentPage() }} of {{ $products->lastPage() }}</p>
                </div>
                <div class="flex items-center gap-2">
                    {{-- ប៊ូតុង ថយក្រោយ (Previous) --}}
                    @if ($products->onFirstPage())
                        <button disabled
                            class="flex items-center gap-1.5 cursor-not-allowed rounded-lg border px-3 py-1.5 text-sm font-medium
                   border-slate-200 bg-slate-50 text-slate-300
                  ">
                            <i data-lucide="arrow-left" class="h-4 w-4"></i>
                            <span class="hidden sm:inline">Previous</span>
                        </button>
                    @else
                        <a href="{{ $products->previousPageUrl() }}"
                            class="flex items-center gap-1.5 rounded-lg border px-3 py-1.5 text-sm font-medium transition
                   border-slate-200 bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-900
                  ">
                            <i data-lucide="arrow-left" class="h-4 w-4"></i>
                            <span class="hidden sm:inline">Previous</span>
                        </a>
                    @endif

                    
                    @if ($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}"
                            class="flex items-center gap-1.5 rounded-lg border px-3 py-1.5 text-sm font-medium transition
                   border-slate-200 bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-900
                  ">
                            <span class="hidden sm:inline">Next</span>
                            <i data-lucide="arrow-right" class="h-4 w-4"></i>
                        </a>
                    @else
                        <button disabled
                            class="flex items-center gap-1.5 cursor-not-allowed rounded-lg border px-3 py-1.5 text-sm font-medium
                   border-slate-200 bg-slate-50 text-slate-300
                  ">
                            <span class="hidden sm:inline">Next</span>
                            <i data-lucide="arrow-right" class="h-4 w-4"></i>
                        </button>
                    @endif
                </div>

            </div>
        </div>        
    </div>

@endsection
