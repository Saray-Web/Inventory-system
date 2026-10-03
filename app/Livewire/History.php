<?php

namespace App\Livewire;

use App\Models\stockIn;
use App\Models\stockOut;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class History extends Component
{
    use WithPagination;

    public $search = '';
    public $type = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 5;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'type', 'dateFrom', 'dateTo');
        $this->resetPage();
        $this->dispatch('history-filters-reset');
    }

    public function render()
    {

        $stockIns = StockIn::with('product.category')->get();
        foreach ($stockIns as $stockIn){
            $stockIn->type = 'Received';
            $stockIn->movement_type = 'stock_in';
        }
        $stockOuts = StockOut::with('product.category')->get();
        foreach ($stockOuts as $stockOut){
            $stockOut->type = 'Dispatched';
            $stockOut->movement_type = 'stock_out';
        }
        $history = $stockIns->concat($stockOuts)
            ->filter(function ($item) {
                $search = strtolower(trim($this->search));
                if ($search !== '' && ! str_contains(strtolower($item->reference_no ?? ''), $search)
                    && ! str_contains(strtolower($item->product?->name ?? ''), $search)
                    && ! str_contains(strtolower($item->product?->sku ?? ''), $search)) {
                    return false;
                }
                if ($this->type !== '' && $item->movement_type !== $this->type) {
                    return false;
                }
                $date = $item->created_at->toDateString();

                return ($this->dateFrom === '' || $date >= $this->dateFrom)
                    && ($this->dateTo === '' || $date <= $this->dateTo);
            })
            ->sortByDesc('created_at')
            ->values();

        $perPage = max(1, (int) $this->perPage);
        $lastPage = max(1, (int) ceil($history->count() / $perPage));
        $currentPage = min(max(1, (int) $this->getPage()), $lastPage);

        $itemsForCurrentPage = $history->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $allhistory = new LengthAwarePaginator(
            $itemsForCurrentPage,
            $history->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'pageName' => 'page']
        );

        return view('livewire.history', compact('allhistory'));
    }
}
