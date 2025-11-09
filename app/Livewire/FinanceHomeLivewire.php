<?php

namespace App\Livewire;

use App\Models\FinancialRecord;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class FinanceHomeLivewire extends Component
{
    use WithPagination, WithFileUploads;

    #[Url] public string $search = '';
    #[Url] public string $type   = ''; // income|expense|''(all)
    #[Url] public ?string $date_from = null;
    #[Url] public ?string $date_to   = null;

    public int $perPage = 20;
    public bool $confirmingDelete = false;
    public ?int $deleteId = null;

    protected $queryString = ['search','type','date_from','date_to'];

    protected $listeners = ['swalConfirmedDelete' => 'deleteConfirmed'];

    public function updatingSearch(){ $this->resetPage(); }
    public function updatingType(){   $this->resetPage(); }
    public function updatingDateFrom(){ $this->resetPage(); }
    public function updatingDateTo(){   $this->resetPage(); }

    public function render(): View
    {
        $records = FinancialRecord::query()
            ->owned()
            ->filter([
                'search'    => $this->search,
                'type'      => $this->type,
                'date_from' => $this->date_from,
                'date_to'   => $this->date_to,
            ])
            ->orderByDesc('transacted_at')
            ->orderByDesc('id')
            ->paginate($this->perPage);

        return view('livewire.finance-home-livewire', compact('records'));
    }

    public function askDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->dispatch('swal:confirm-delete'); // ditangkap di blade -> SweetAlert2
    }

    public function deleteConfirmed(): void
    {
        if(!$this->deleteId) return;
        FinancialRecord::owned()->whereKey($this->deleteId)->delete();
        $this->deleteId = null;
        $this->dispatch('swal:toast', type: 'success', message: 'Data dihapus.');
    }
}
