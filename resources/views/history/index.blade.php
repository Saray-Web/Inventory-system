@extends('Layouts.app')

@section('page-title', 'Stock History')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Stock History</h1>
                <p class="mt-1 text-sm text-slate-500">Complete log of all inventory movements and transactions.</p>
            </div>
            <button
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <i data-lucide="download" class="h-4 w-4"></i> Export
            </button>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <div class="page-card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Transactions</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalTransactions }}</p>
                    </div>
                    <div class="grid h-11 w-11 place-items-center rounded-2xl bg-blue-50 text-blue-600"><i
                            data-lucide="clipboard-list" class="h-5 w-5"></i></div>
                </div>
            </div>
            <div class="page-card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Stock In Transactions</p>
                        <p class="mt-2 text-3xl font-bold text-emerald-600">+ {{ $Total_Stock_In_Transactions }}</p>
                    </div>
                    <div class="grid h-11 w-11 place-items-center rounded-2xl bg-emerald-50 text-emerald-600"><i
                            data-lucide="arrow-down-to-line" class="h-5 w-5"></i></div>
                </div>
            </div>
            <div class="page-card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Stock Out Transactions</p>
                        <p class="mt-2 text-3xl font-bold text-rose-600">&minus; {{ $Total_Stock_Out_Transactions }}</p>
                    </div>
                    <div class="grid h-11 w-11 place-items-center rounded-2xl bg-rose-50 text-rose-600"><i
                            data-lucide="arrow-up-from-line" class="h-5 w-5"></i></div>
                </div>
            </div>
        </div>

        <livewire:history />
        
    </div>
@endsection
