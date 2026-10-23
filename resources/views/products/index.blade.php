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

        {{-- ===== Product cards ===== --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
            @forelse ($products as $product)
                @php
                    if ($product->quantity === 0) {
                        $stockLabel = 'Out of Stock';
                        $stockClass = 'border-red-200 bg-red-50 text-red-700';
                        $stockIconClass = 'text-red-500';
                    } elseif ($product->quantity <= $product->minimum_stock) {
                        $stockLabel = 'Low Stock';
                        $stockClass = 'border-amber-200 bg-amber-50 text-amber-700';
                        $stockIconClass = 'text-amber-500';
                    } else {
                        $stockLabel = 'In Stock';
                        $stockClass = 'border-emerald-200 bg-emerald-50 text-emerald-700';
                        $stockIconClass = 'text-emerald-500';
                    }
                @endphp

                <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="relative aspect-[16/9] bg-slate-100">
                        @if ($product->image)
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center text-slate-400">
                                <i data-lucide="image" class="h-12 w-12"></i>
                            </div>
                        @endif

                        <span class="absolute right-3 top-3 rounded-full px-2.5 py-1 text-xs font-semibold {{ $product->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-red-600' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div class="space-y-4 p-5">
                        <div>
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="line-clamp-1 text-base font-bold text-slate-900">{{ $product->name }}</h3>
                                <p class="shrink-0 text-base font-bold text-slate-900">${{ number_format($product->price, 2) }}</p>
                            </div>
                            <p class="mt-1 font-mono text-xs text-slate-500">{{ $product->sku }}</p>
                            <p class="mt-2 inline-flex items-center gap-1.5 text-sm text-slate-600">
                                <i data-lucide="tag" class="h-3.5 w-3.5"></i>
                                {{ $product->category->name }}
                            </p>
                        </div>

                        <div class="rounded-xl border px-3.5 py-3 {{ $stockClass }}">
                            <div class="flex items-center justify-between gap-3">
                                <span class="inline-flex items-center gap-1.5 text-sm font-semibold">
                                    <i data-lucide="{{ $product->quantity === 0 ? 'circle-x' : 'package-check' }}" class="h-4 w-4 {{ $stockIconClass }}"></i>
                                    {{ $stockLabel }}
                                </span>
                                <span class="text-sm font-bold">{{ number_format($product->quantity) }} units</span>
                            </div>
                            <p class="mt-1 text-xs opacity-80">Minimum stock: {{ number_format($product->minimum_stock) }} units</p>
                        </div>

                        <div class="flex items-center justify-between border-t border-slate-100 pt-4">
                            <span class="text-xs text-slate-400">#{{ $products->firstItem() + $loop->index }}</span>
                            <div class="flex gap-2">
                                <a href="{{ route('product.edit', $product) }}"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-blue-200 text-blue-700 transition hover:bg-blue-50"
                                    aria-label="Edit {{ $product->name }}">
                                    <i data-lucide="square-pen" class="h-4 w-4"></i>
                                </a>
                                <form action="{{ route('product.destroy', $product) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to remove this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-amber-200 text-amber-700 transition hover:bg-amber-50"
                                        aria-label="Deactivate or remove {{ $product->name }}">
                                        <i data-lucide="archive" class="h-4 w-4"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center text-slate-500">
                    <i data-lucide="package-search" class="mx-auto mb-3 h-10 w-10 text-slate-400"></i>
                    No products found.
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-5 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
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
