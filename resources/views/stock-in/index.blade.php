@extends('Layouts.app')

@section('page-title', 'Stock In')

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
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">
                    Stock In
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    Manage and track incoming inventory.
                </p>
            </div>
            <a href="{{ route('stockIn.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl
                   bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white
                   shadow-sm transition hover:bg-blue-700">
                <i data-lucide="package-plus" class="h-4 w-4"></i>
                Receive Stock
            </a>
        </div>
        {{-- Summary Cards --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <div class="page-card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Total Received
                        </p>

                        <p class="mt-2 text-3xl font-bold text-slate-900">
                            {{ $stockIns->sum('quantity') }}
                        </p>
                    </div>

                    <div
                        class="grid h-11 w-11 place-items-center rounded-2xl
                            bg-emerald-50 text-emerald-600">

                        <i data-lucide="arrow-down-to-line" class="h-5 w-5"></i>
                    </div>
                </div>
            </div>
            <div class="page-card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Transactions
                        </p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">
                            {{ $stockIns->count() }}
                        </p>
                    </div>
                    <div
                        class="grid h-11 w-11 place-items-center rounded-2xl
                            bg-blue-50 text-blue-600">

                        <i data-lucide="clipboard-list" class="h-5 w-5"></i>
                    </div>
                </div>
            </div>
            <div class="page-card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Latest Activity
                        </p>
                        @if ($latestStockIn)
                            <p class="mt-2 text-lg font-bold text-slate-900 truncate max-w-[180px]">
                                {{ $latestStockIn->product->name }}
                            </p>
                            <p class="mt-2 text-sm font-bold text-slate-900 truncate max-w-[180px]">
                                + {{ $latestStockIn->created_at->format('d M Y · h:i A') }}
                            </p>
                        @else
                            <p class="mt-2 text-lg font-bold text-slate-900">
                                No activity
                            </p>
                        @endif
                    </div>
                    <div
                        class="grid h-11 w-11 place-items-center rounded-2xl
                            bg-violet-50 text-violet-600">
                        <i data-lucide="clock-3" class="h-5 w-5"></i>
                    </div>
                </div>
            </div>
        </div>
        {{-- Table --}}
        <div class="page-card overflow-hidden">

            {{-- Table Header --}}
            <div
                class="flex flex-col gap-4 border-b border-slate-100 p-5
                    sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <h2 class="font-bold text-slate-900">
                        Stock In History
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        View all incoming stock transactions.
                    </p>
                </div>


                {{-- Search --}}
                <div class="relative">
                    <i data-lucide="search"
                        class="absolute left-3 top-1/2 h-4 w-4
                          -translate-y-1/2 text-slate-400"></i>
                    <input type="text" placeholder="Search product..."
                        class="w-full rounded-xl border border-slate-200
                           bg-white py-2.5 pl-9 pr-4 text-sm
                           outline-none transition
                           focus:border-blue-500
                           focus:ring-2 focus:ring-blue-100
                           sm:w-64">
                </div>
            </div>
            {{-- Responsive Table --}}
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] text-left">
                    <thead class="border-b border-slate-200 bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wide text-slate-500">
                                #
                            </th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wide text-slate-500">
                                Ref No.
                            </th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wide text-slate-500">
                                Product
                            </th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wide text-slate-500">
                                SKU
                            </th>

                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wide text-slate-500">
                                Quantity
                            </th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wide text-slate-500">
                                Note
                            </th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wide text-slate-500">
                                Date
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 ">
                        {{-- Mock row for design only --}}
                        @forelse ($stockIns as $stockIn)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-6 py-4 text-sm font-medium text-slate-500">
                                    {{ $stockIns->firstItem() + $loop->index }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $stockIn->reference_no ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        {{-- Product Image Container --}}
                                        <div
                                            class="h-10 w-10 shrink-0 overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                            @if ($stockIn->product && $stockIn->product->image)
                                                <img src="{{ $stockIn->product->image }}"
                                                    alt="{{ $stockIn->product->name }}" class="h-full w-full object-cover">
                                            @else
                                                <div class="grid h-full w-full place-items-center text-slate-400">
                                                    <i data-lucide="image" class="h-5 w-5"></i>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Product Name & Category --}}
                                        <div>
                                            <p class="font-semibold text-slate-800">
                                                {{ $stockIn->product->name ?? 'N/A' }}
                                            </p>
                                            <p class="text-xs text-slate-400">
                                                {{ $stockIn->product->category->name ?? 'Uncategorized' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $stockIn->product->sku }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center rounded-full
                                         bg-emerald-50 px-3 py-1 text-xs
                                         font-bold text-emerald-700">
                                        {{ number_format($stockIn->quantity) }}
                                    </span>
                                </td>
                                <td
                                    class="max-w-xs truncate px-6 py-4 text-sm
                                   text-slate-500">
                                    {{ $stockIn->note ?? '_' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $stockIn->created_at->format('d M Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="grid h-16 w-16 place-items-center
                                            rounded-2xl bg-slate-100
                                            text-slate-400">
                                            <i data-lucide="package-open" class="h-7 w-7"></i>
                                        </div>
                                        <h3 class="mt-4 font-semibold text-slate-700">
                                            No stock received yet
                                        </h3>
                                        <p class="mt-1 text-sm text-slate-400">
                                            Start by receiving your first stock.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex border-t border-slate-200 px-6 py-4 justify-between">
                <div>
                    <p> Page {{ $stockIns->currentPage() }} of {{ $stockIns->lastPage() }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @if ($stockIns->onFirstPage())
                        <button disabled
                            class="flex items-center gap-1.5 cursor-not-allowed rounded-lg border px-3 py-1.5 text-sm font-medium
                   border-slate-200 bg-slate-50 text-slate-300
                  ">
                            <i data-lucide="arrow-left" class="h-4 w-4"></i>
                            <span class="hidden sm:inline">Previous</span>
                        </button>
                    @else
                        <a href="{{ $stockIns->previousPageUrl() }}"
                            class="flex items-center gap-1.5 rounded-lg border px-3 py-1.5 text-sm font-medium transition
                   border-slate-200 bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-900
                  ">
                            <i data-lucide="arrow-left" class="h-4 w-4"></i>
                            <span class="hidden sm:inline">Previous</span>
                        </a>
                    @endif

                    
                    @if ($stockIns->hasMorePages())
                        <a href="{{ $stockIns->nextPageUrl() }}"
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
    </div>
@endsection
