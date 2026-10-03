<div class="space-y-6">
    <div class="page-card p-4">
        <form wire:submit.prevent class="flex flex-col gap-4 xl:flex-row xl:items-center">
            <div class="relative flex-1">
                <label for="search_history" class="sr-only">Search product, SKU, or reference</label>
                <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                <input type="text" id="search_history" wire:model.live="search"
                    placeholder="Search product, SKU, or reference..."
                    class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-9 pr-4 text-sm outline-none transition placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
            </div>
            <div class="relative">
                <label for="type" class="sr-only">Filter by type</label>
                <i data-lucide="filter" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                <select id="type" wire:model.live="type"
                    class="w-full appearance-none rounded-xl border border-slate-200 bg-white py-2.5 pl-9 pr-10 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 xl:w-44">
                    <option value="">All Types</option>
                    <option value="stock_in">Stock In</option>
                    <option value="stock_out">Stock Out</option>
                </select>
                <i data-lucide="chevron-down"
                    class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
            </div>
            <div wire:ignore
                x-on:history-filters-reset.window="$el.querySelectorAll('input[type=date]').forEach((input) => input.value = '')"
                class="flex w-full flex-col gap-2 rounded-xl border border-slate-200 bg-white p-2 sm:flex-row sm:items-center sm:gap-0 xl:w-auto">
                <div class="flex min-w-0 flex-1 items-center">
                    <label for="date_from" class="sr-only">From date</label><i data-lucide="calendar"
                        class="ml-1 h-4 w-4 shrink-0 text-slate-400"></i>
                    <input type="date" id="date_from" wire:model.live="dateFrom" max="{{ now()->format('Y-m-d') }}"
                        class="min-w-0 w-full border-none bg-transparent px-2 py-1.5 text-sm outline-none focus:ring-0 sm:w-32">
                </div>
                <span class="hidden px-1 text-slate-300 sm:inline">&ndash;</span>
                <div class="flex min-w-0 flex-1 items-center border-t border-slate-100 pt-2 sm:border-0 sm:pt-0">
                    <label for="date_to" class="sr-only">To date</label><input type="date" id="date_to"
                        wire:model.live="dateTo" max="{{ now()->format('Y-m-d') }}"
                        class="min-w-0 w-full border-none bg-transparent px-2 py-1.5 text-sm outline-none focus:ring-0 sm:w-32">
                </div>
            </div>
            <button type="button" wire:click="resetFilters"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50 sm:w-auto"><i
                    data-lucide="rotate-ccw" class="h-4 w-4"></i> Reset</button>
        </form>
    </div>
    <div class="page-card overflow-hidden">
        <div class="border-b border-slate-100 p-5">
            <h2 class="font-bold text-slate-900">Transaction Log</h2>
            <p class="mt-1 text-sm text-slate-500">Showing {{ $allhistory->total() }}
                {{ Str::plural('transaction', $allhistory->count()) }}</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left">
                <thead class="border-b border-slate-200 bg-slate-50 sticky top-0 z-10">
                    <tr>
                        @foreach (['#', 'Date & Time', 'Type', 'Product', 'SKU', 'Quantity', 'Reference', 'Note'] as $heading)
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wide text-slate-500">
                                {{ $heading }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 ">
                    @forelse ($allhistory as $item)
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-6 py-4 text-sm font-medium text-slate-500">{{ $allhistory->firstItem() + $loop->index }}</td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-slate-800">{{ $item->created_at->format('Y-m-d') }}
                                </p>
                                <p class="text-xs text-slate-400">{{ $item->created_at->format('h:i A') }}</p>
                            </td>
                            <td class="px-6 py-4"><span
                                    class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold {{ $item->movement_type === 'stock_in' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}"><span
                                        class="h-1.5 w-1.5 rounded-full {{ $item->movement_type === 'stock_in' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>{{ $item->type }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-10 w-10 shrink-0 overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                        @if ($item->product?->image)
                                            <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}"
                                            class="h-full w-full object-cover">@else<div
                                                class="grid h-full w-full place-items-center text-slate-400"><i
                                                    data-lucide="image" class="h-5 w-5"></i></div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">
                                            {{ $item->product?->name ?? 'Deleted product' }}</p>
                                        <p class="text-xs text-slate-400">
                                            {{ $item->product?->category?->name ?? 'Uncategorized' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ $item->product?->sku ?? '-' }}</td>
                            <td class="px-6 py-4"><span
                                    class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">{{ $item->quantity }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-blue-600">{{ $item->reference_no }}</td>
                            <td class="max-w-[160px] truncate px-6 py-4 text-sm text-slate-500">
                                {{ $item->note ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-sm text-slate-500">No transactions
                                match these filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 px-6 py-4 flex justify-between">

            <div class="flex items-center justify-between">

                <div>
                    <p> Page {{ $allhistory->currentPage() }} of {{ $allhistory->lastPage() }}</p>
                </div>

            </div>
            <div class=" flex items-center justify-between gap-2">
                @if ($allhistory->onFirstPage())
                    <button disabled
                        class="flex items-center gap-1.5 cursor-not-allowed rounded-lg border px-3 py-1.5 text-sm font-medium
                   border-slate-200 bg-slate-50 text-slate-300
                  ">
                        <i data-lucide="arrow-left" class="h-4 w-4"></i>
                        <span class="hidden sm:inline">Previous</span>
                    </button>
                @else
                <button wire:click="previousPage" wire:loading.attr="disabled"
                            class="flex items-center gap-1.5 rounded-lg border px-3 py-1.5 text-sm font-medium transition
                   border-slate-200 bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer
                  ">
                            <i data-lucide="arrow-left" class="h-4 w-4"></i>
                            <span class="hidden sm:inline">Previous</span>
                        </button>
                @endif
                @if ($allhistory->hasMorePages())
                        <button wire:click="nextPage" wire:loading.attr="disabled"
                            class="flex items-center gap-1.5 rounded-lg border px-3 py-1.5 text-sm font-medium transition
                   border-slate-200 bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer
                  ">
                            <span class="hidden sm:inline">Next</span>
                            <i data-lucide="arrow-right" class="h-4 w-4"></i>
                        </button>
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
