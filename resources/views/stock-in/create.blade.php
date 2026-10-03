@extends('Layouts.app')

@section('page-title', 'Receive Stock')

@section('content')

    <div class="mx-auto max-w-3xl space-y-6">

        {{-- Header --}}
        <div>

            <div class="flex items-center gap-2 text-sm text-slate-400">

                <a href="#" class="hover:text-blue-600">
                    Stock In
                </a>

                <i data-lucide="chevron-right" class="h-4 w-4"></i>

                <span>
                    Receive Stock
                </span>

            </div>

            <h1 class="mt-3 text-2xl font-bold text-slate-900">
                Receive Stock
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Add incoming inventory to your warehouse.
            </p>

        </div>


        {{-- Form Card --}}
        <div class="page-card p-6 sm:p-8">

            <form action="{{ route('stockIn.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Product
                    </label>
                    <select name="product_id"
                        class="w-full rounded-xl border border-slate-200
                           bg-white px-4 py-3 text-sm text-slate-700
                           outline-none transition
                           focus:border-blue-500
                           focus:ring-2 focus:ring-blue-100">
                        <option value="">
                            Select product
                        </option>
                        @foreach ($products as $product)
                            {{-- <option value="{{ $product->id }}">
                            {{ old('product_id') == $product->id ? 'selected' : '' }}
                            {{ $product->name }} - {{ $product->sku }}
                        </option> --}}
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}
                                data-quantity="{{ $product->quantity }}">
                                {{ $product->name }} - {{ $product->sku }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                {{-- Current Stock --}}
                <div class="rounded-xl border border-blue-100
                        bg-blue-50 p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="grid h-10 w-10 place-items-center
                                    rounded-xl bg-white text-blue-600">

                                <i data-lucide="warehouse" class="h-5 w-5"></i>

                            </div>

                            <div>

                                <p class="text-xs font-medium text-slate-500">
                                    Current Stock
                                </p>

                                <p id="currentStock" class="font-bold text-slate-900">
                                    0 units
                                </p>

                            </div>

                        </div>

                        <span class="text-xs font-semibold text-blue-600">
                            Available
                        </span>

                    </div>

                </div>


                {{-- Quantity --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Quantity
                    </label>

                    <input name="quantity" type="number" min="1" placeholder="Enter quantity"
                        class="w-full rounded-xl border border-slate-200
                           px-4 py-3 text-sm outline-none transition
                           focus:border-blue-500
                           focus:ring-2 focus:ring-blue-100">

                    <p class="mt-1.5 text-xs text-slate-400">
                        Enter the number of units received.
                    </p>

                </div>


                {{-- Note --}}
                <div>

                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Note
                        <span class="font-normal text-slate-400">
                            (Optional)
                        </span>
                    </label>

                    <textarea name="note" rows="4" placeholder="Example: Received from supplier..."
                        class="w-full rounded-xl border border-slate-200
                           px-4 py-3 text-sm outline-none transition
                           focus:border-blue-500
                           focus:ring-2 focus:ring-blue-100"></textarea>

                </div>


                {{-- Preview --}}
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">

                    <p class="mb-3 text-sm font-semibold text-slate-700">
                        Stock Summary
                    </p>

                    <div class="space-y-2 text-sm">

                        <div class="flex justify-between">

                            <span class="text-slate-500">
                                Current stock
                            </span>

                            <span id="summaryCurrentStock" class="font-semibold text-slate-700">
                                0
                            </span>

                        </div>

                        <div class="flex justify-between">

                            <span class="text-slate-500">
                                Incoming stock
                            </span>

                            <span id="incomingStock" class="font-semibold text-emerald-600">
                                + 0
                            </span>

                        </div>

                        <div class="border-t border-slate-200 pt-2
                                flex justify-between">

                            <span class="font-semibold text-slate-700">
                                New stock
                            </span>

                            <span id="newStock" class="font-bold text-slate-900">
                                0
                            </span>

                        </div>

                    </div>

                </div>


                {{-- Buttons --}}
                <div
                    class="flex flex-col-reverse gap-3 border-t
                        border-slate-100 pt-6 sm:flex-row sm:justify-end">

                    <a href="{{ route('stockIn.index') }}"
                        class="inline-flex items-center justify-center rounded-xl
                           border border-slate-200 px-5 py-2.5 text-sm
                           font-semibold text-slate-600 transition
                           hover:bg-slate-50">

                        Cancel

                    </a>

                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2
                           rounded-xl bg-blue-600 px-5 py-2.5 text-sm
                           font-semibold text-white shadow-sm transition
                           hover:bg-blue-700">

                        <i data-lucide="package-plus" class="h-4 w-4"></i>

                        Receive Stock

                    </button>

                </div>

            </form>

        </div>

    </div>
    <script>
    const productSelect = document.querySelector('[name="product_id"]');
    const quantityInput = document.querySelector('[name="quantity"]');

    const currentStock = document.getElementById('currentStock');
    const summaryCurrentStock = document.getElementById('summaryCurrentStock');
    const incomingStock = document.getElementById('incomingStock');
    const newStock = document.getElementById('newStock');

    function updateStockSummary() {

        const selectedOption =
            productSelect.options[productSelect.selectedIndex];

        const currentQuantity =
            Number(selectedOption.dataset.quantity) || 0;

        const incomingQuantity =
            Number(quantityInput.value) || 0;

        const totalQuantity =
            currentQuantity + incomingQuantity;

        // Current Stock
        currentStock.textContent = `${currentQuantity} units`;

        // Summary
        summaryCurrentStock.textContent = currentQuantity;

        // Incoming Stock
        incomingStock.textContent = `+${incomingQuantity}`;

        // New Stock
        newStock.textContent = totalQuantity;
    }

    productSelect.addEventListener('change', updateStockSummary);

    quantityInput.addEventListener('input', updateStockSummary);

    // Run when page loads
    updateStockSummary();
</script>

@endsection
