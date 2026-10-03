@extends('layouts.app')

@section('page-title', 'Stock Out Management')

@section('content')
    <div class="space-y-6">

        {{-- Notification Message --}}
        @if (session('success'))
            <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
                <i data-lucide="check-circle" class="h-5 w-5 shrink-0 text-emerald-600"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Stock Out</h1>
                <p class="mt-1 text-sm text-slate-500">Manage and track outgoing inventory items.</p>
            </div>

            <a href="{{ route('stockOut.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700">
                <i data-lucide="package-minus" class="h-4 w-4"></i>
                Issue Stock Out
            </a>
        </div>

        {{-- Summary Cards --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            {{-- Total Issued --}}
            <div class="page-card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Quantity Issued</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($totalIssued ?? 0) }}</p>
                    </div>
                    <div class="grid h-11 w-11 place-items-center rounded-2xl bg-rose-50 text-rose-600">
                        <i data-lucide="arrow-up-from-line" class="h-5 w-5"></i>
                    </div>
                </div>
            </div>

            {{-- Total Transactions --}}
            <div class="page-card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Transactions</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($totalTransactions ?? 0) }}</p>
                    </div>
                    <div class="grid h-11 w-11 place-items-center rounded-2xl bg-blue-50 text-blue-600">
                        <i data-lucide="clipboard-list" class="h-5 w-5"></i>
                    </div>
                </div>
            </div>

            {{-- Latest Activity --}}
            <div class="page-card p-5">
                <div class="flex items-center justify-between">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-slate-500">Latest Transaction</p>
                        @if ($latestActivity)
                            <p class="mt-2 text-base font-bold text-slate-900 truncate">
                                {{ $latestActivity->reference_no }}
                            </p>
                            <p class="text-xs text-rose-600 font-medium truncate">
                                {{ $latestActivity->product->name ?? 'N/A' }} (−{{ $latestActivity->quantity }})
                            </p>
                        @else
                            <p class="mt-2 text-sm font-semibold text-slate-400">No activity yet</p>
                        @endif
                    </div>
                    <div class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-violet-50 text-violet-600">
                        <i data-lucide="clock-3" class="h-5 w-5"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="page-card overflow-hidden">
            {{-- Table Header & Search --}}
            <div class="flex flex-col gap-4 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-bold text-slate-900">Stock Out History</h2>
                    <p class="mt-1 text-sm text-slate-500">View all completed outgoing stock transactions.</p>
                </div>

                <form method="GET" action="{{ route('stockOut.index') }}" class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or SKU..."
                        class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-9 pr-4 text-sm outline-none transition focus:border-rose-500 focus:ring-2 focus:ring-rose-100 sm:w-64">
                </form>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] text-left">
                    <thead class="border-b border-slate-200 bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wide text-slate-500">#</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wide text-slate-500">Ref No.</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wide text-slate-500">Product</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wide text-slate-500">SKU</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wide text-slate-500">Quantity</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wide text-slate-500">Note</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wide text-slate-500">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($stockOuts as $item)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-6 py-4 text-sm text-slate-500">
                                   {{ $stockOuts->firstItem() + $loop->index }}
                                </td>

                                <td class="px-6 py-4 text-sm font-semibold text-rose-600">
                                    {{ $item->reference_no }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-10 w-10 shrink-0 overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                            @if ($item->product && $item->product->image)
                                                <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}"
                                                    class="h-full w-full object-cover">
                                            @else
                                                <div class="grid h-full w-full place-items-center text-slate-400">
                                                    <i data-lucide="image" class="h-5 w-5"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800">{{ $item->product->name ?? 'N/A' }}</p>
                                            <p class="text-xs text-slate-400">
                                                {{ $item->product->category->name ?? 'Uncategorized' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $item->product->sku ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center rounded-full bg-rose-50 px-3 py-1 text-xs font-bold text-rose-700">
                                        − {{ number_format($item->quantity) }}
                                    </span>
                                </td>
                                <td class="max-w-xs truncate px-6 py-4 text-sm text-slate-500">
                                    {{ $item->note ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $item->created_at->format('d M Y · h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="grid h-16 w-16 place-items-center rounded-2xl bg-slate-100 text-slate-400">
                                            <i data-lucide="package-open" class="h-7 w-7"></i>
                                        </div>
                                        <h3 class="mt-4 font-semibold text-slate-700">No stock out transactions found</h3>
                                        <p class="mt-1 text-sm text-slate-400">Start by creating a new stock out record.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="flex border-t border-slate-200 px-6 py-4 justify-between">
                <div>
                    <p> Page {{ $stockOuts->currentPage() }} of {{ $stockOuts->lastPage() }}</p>
                </div>
                <div class="flex items-center gap-2">
                    {{-- ប៊ូតុង ថយក្រោយ (Previous) --}}
                    @if ($stockOuts->onFirstPage())
                        <button disabled
                            class="flex items-center gap-1.5 cursor-not-allowed rounded-lg border px-3 py-1.5 text-sm font-medium
                   border-slate-200 bg-slate-50 text-slate-300
                  ">
                            <i data-lucide="arrow-left" class="h-4 w-4"></i>
                            <span class="hidden sm:inline">Previous</span>
                        </button>
                    @else
                        <a href="{{ $stockOuts->previousPageUrl() }}"
                            class="flex items-center gap-1.5 rounded-lg border px-3 py-1.5 text-sm font-medium transition
                   border-slate-200 bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-900
                  ">
                            <i data-lucide="arrow-left" class="h-4 w-4"></i>
                            <span class="hidden sm:inline">Previous</span>
                        </a>
                    @endif

                    
                    @if ($stockOuts->hasMorePages())
                        <a href="{{ $stockOuts->nextPageUrl() }}"
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
