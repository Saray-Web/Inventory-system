@extends('Layouts.app')

@section('page-title', 'Dashboard')

@section('content')

    <div class="space-y-6">

        {{-- =========================
        Welcome / Header
    ========================== --}}
        <section
            class="relative overflow-hidden rounded-3xl bg-slate-950 px-6 py-7 text-white shadow-xl shadow-slate-900/10 sm:px-8">

            <div class="absolute -right-20 -top-24 h-64 w-64 rounded-full bg-cyan-400/20 blur-3xl"></div>

            <div class="absolute right-32 bottom-0 h-32 w-32 rounded-full bg-blue-600/30 blur-2xl"></div>

            <div class="relative flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">

                <div>

                    <p class="mb-2 text-sm font-medium text-cyan-300">
                        {{ now()->format('l, d M Y') }}
                    </p>

                    <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">
                        Inventory, at a glance.
                    </h1>

                    <p class="mt-2 max-w-xl text-sm text-slate-400">
                        Track stock movement and keep every product ready for the next order.
                    </p>

                </div>

                <a href="{{ route('product.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-slate-900 shadow-lg transition hover:-translate-y-0.5 hover:bg-cyan-50">

                    <i data-lucide="plus" class="h-4 w-4"></i>

                    Add product

                </a>

            </div>

        </section>


        {{-- =========================
        Statistics Cards
    ========================== --}}
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">


            {{-- Total Products --}}
            <article class="page-card group p-5 transition duration-300 hover:-translate-y-1">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Total Products
                        </p>

                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                            {{ number_format($totalProducts) }}
                        </p>

                        <p class="mt-2 text-xs text-slate-400">
                            Catalog items
                        </p>

                    </div>

                    <span
                        class="grid h-11 w-11 place-items-center rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-400 text-white shadow-lg">

                        <i data-lucide="package" class="h-5 w-5"></i>

                    </span>

                </div>

            </article>


            {{-- Total Categories --}}
            <article class="page-card group p-5 transition duration-300 hover:-translate-y-1">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Categories
                        </p>

                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                            {{ number_format($totalCategories) }}
                        </p>

                        <p class="mt-2 text-xs text-slate-400">
                            Product groups
                        </p>

                    </div>

                    <span
                        class="grid h-11 w-11 place-items-center rounded-2xl bg-gradient-to-br from-violet-500 to-fuchsia-400 text-white shadow-lg">

                        <i data-lucide="tags" class="h-5 w-5"></i>

                    </span>

                </div>

            </article>


            {{-- Total Stock In --}}
            <article class="page-card group p-5 transition duration-300 hover:-translate-y-1">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Stock Received
                        </p>

                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                            {{ number_format($totalStockIn) }}
                        </p>

                        <p class="mt-2 text-xs text-slate-400">
                            Units added
                        </p>

                    </div>

                    <span
                        class="grid h-11 w-11 place-items-center rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-400 text-white shadow-lg">

                        <i data-lucide="arrow-down-to-line" class="h-5 w-5"></i>

                    </span>

                </div>

            </article>


            {{-- Total Stock Out --}}
            <article class="page-card group p-5 transition duration-300 hover:-translate-y-1">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Stock Dispatched
                        </p>

                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                            {{ number_format($totalStockOut) }}
                        </p>

                        <p class="mt-2 text-xs text-slate-400">
                            Units removed
                        </p>

                    </div>

                    <span
                        class="grid h-11 w-11 place-items-center rounded-2xl bg-gradient-to-br from-orange-500 to-rose-400 text-white shadow-lg">

                        <i data-lucide="arrow-up-from-line" class="h-5 w-5"></i>

                    </span>

                </div>

            </article>

        </section>


        {{-- =========================
        Inventory Health
    ========================== --}}
        <section class="grid gap-5 lg:grid-cols-5">


            {{-- Available Stock --}}
            <article class="page-card lg:col-span-3">

                <div class="flex items-start justify-between border-b border-slate-100 p-6">

                    <div>

                        <h2 class="font-bold text-slate-900">
                            Inventory health
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            A live summary of available stock.
                        </p>

                    </div>

                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">

                        Healthy

                    </span>

                </div>


                <div class="grid gap-6 p-6 sm:grid-cols-2">


                    {{-- Total Available Units --}}
                    <div class="rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-500 p-5 text-white">

                        <i data-lucide="warehouse" class="mb-8 h-6 w-6 text-cyan-100">
                        </i>

                        <p class="text-sm text-blue-100">
                            Available units
                        </p>

                        <p class="mt-1 text-4xl font-bold">
                            {{ number_format($CurrentStock) }}
                        </p>

                    </div>


                    {{-- Stock Status --}}
                    {{-- Stock Status --}}
                    <div class="flex flex-col justify-center">

                        @php
                            // គណនាភាគរយភ្លាមៗក្នុង Blade (បើស្តុកចូល = 0 ឲ្យវា 0%)
                            $percent = $totalStockIn > 0 ? min(round(($CurrentStock / $totalStockIn) * 100), 100) : 0;
                        @endphp

                        <div class="mb-3 flex justify-between text-sm">
                            <span class="font-medium text-slate-700">
                                Capacity status
                            </span>

                            <span class="font-bold text-blue-600">
                                {{ $percent }}% In stock
                            </span>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="h-3 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-gradient-to-r from-blue-600 to-cyan-400 transition-all duration-500"
                                style="width: {{ $percent }}%;">
                            </div>
                        </div>

                        <p class="mt-4 text-sm leading-6 text-slate-500">
                            Your current inventory has
                            <span class="font-semibold text-slate-700">
                                {{ number_format($CurrentStock) }}
                            </span>
                            units available across
                            <span class="font-semibold text-slate-700">
                                {{ number_format($totalProducts) }}
                            </span>
                            products.
                        </p>

                    </div>

                </div>

            </article>


            {{-- Low Stock --}}
            <article class="page-card lg:col-span-2">

                <div class="border-b border-slate-100 p-6">

                    <h2 class="font-bold text-slate-900">
                        Needs attention
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Products near their minimum level.
                    </p>

                </div>


                <div class="p-6">

                    <div class="flex items-center gap-4">

                        <span class="grid h-14 w-14 place-items-center rounded-2xl bg-amber-50 text-amber-600">

                            <i data-lucide="triangle-alert" class="h-7 w-7"></i>

                        </span>


                        <div>

                            <p class="text-3xl font-bold text-slate-900">
                                {{ number_format($loweStock) }}
                            </p>

                            <p class="text-sm text-slate-500">
                                low-stock products
                            </p>

                        </div>

                    </div>


                    <a href="{{ route('product.index') }}"
                        class="mt-6 flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">

                        Review product stock

                        <i data-lucide="arrow-up-right" class="h-4 w-4"></i>

                    </a>

                </div>

            </article>

        </section>


        {{-- =========================
        Quick Actions
    ========================== --}}
        <section class="page-card p-5 sm:p-6">

            <div class="mb-4">

                <h2 class="font-bold text-slate-900">
                    Quick actions
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Jump straight into frequent tasks.
                </p>

            </div>


            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">


                {{-- Add Product --}}
                <a href="{{ route('product.create') }}"
                    class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 transition hover:border-blue-200 hover:bg-blue-50">

                    <i data-lucide="plus" class="h-5 w-5 text-blue-600"></i>

                    <span class="text-sm font-bold text-slate-700">
                        Add product
                    </span>
                </a>
                {{-- Create Category --}}
                <a href="{{ route('categories.create') }}"
                    class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 transition hover:border-violet-200 hover:bg-violet-50">

                    <i data-lucide="tags" class="h-5 w-5 text-violet-600"></i>

                    <span class="text-sm font-bold text-slate-700">
                        Create category
                    </span>

                </a>
                {{-- Stock In --}}
                <a href="{{ route('stockIn.index') }}"
                    class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 transition hover:border-emerald-200 hover:bg-emerald-50">

                    <i data-lucide="circle-arrow-down" class="h-5 w-5 text-emerald-600">
                    </i>

                    <span class="text-sm font-bold text-slate-700">
                        Receive stock
                    </span>
                </a>
                {{-- Stock Out --}}
                <a href="{{ route('stockOut.index') }}"
                    class="flex items-center gap-3 rounded-xl border border-slate-200 p-4 transition hover:border-orange-200 hover:bg-orange-50">

                    <i data-lucide="circle-arrow-up" class="h-5 w-5 text-orange-600">
                    </i>

                    <span class="text-sm font-bold text-slate-700">
                        Dispatch stock
                    </span>

                </a>

            </div>

        </section>

    </div>

@endsection
