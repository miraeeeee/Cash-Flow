<?php

namespace App\Livewire;

use App\Models\FinancialRecord;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class FinanceDetailLivewire extends Component
{
    use WithFileUploads;

    public ?FinancialRecord $record = null;

    public string $mode = 'show'; // show|create|edit

    #[Validate('required|string|max:160')]
    public string $title = '';

    #[Validate('required|in:income,expense')]
    public string $type = 'expense';

    #[Validate('required|numeric|min:0')]
    public $amount = 0;

    #[Validate('required|date')]
    public string $transacted_at = '';

    #[Validate('nullable|string|max:80')]
    public ?string $category = null;

    #[Validate('nullable|string|max:2000')]
    public ?string $notes = null;

    #[Validate('nullable|image|max:2048')]
    public $cover_upload;

    public function mount(?FinancialRecord $record = null): void
    {
        $this->record = $record?->exists ? $record : null;
        $this->mode = request('mode', $this->record ? 'show' : 'create');

        if ($this->record) {
            $this->title         = $this->record->title;
            $this->type          = $this->record->type;
            $this->amount        = $this->record->amount;
            $this->transacted_at = $this->record->transacted_at->format('Y-m-d');
            $this->category      = $this->record->category;
            $this->notes         = $this->record->notes;
        } else {
            $this->transacted_at = now()->toDateString();
        }
    }

    public function render(): View
    {
        return view('livewire.finance-detail-livewire');
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'user_id'       => auth()->id(),
            'title'         => $this->title,
            'type'          => $this->type,
            'amount'        => (float)$this->amount,
            'transacted_at' => $this->transacted_at,
            'category'      => $this->category,
            'notes'         => $this->notes,
        ];

        if ($this->cover_upload) {
            $path = $this->cover_upload->store('covers', 'public');
            $data['cover'] = $path;

            if ($this->record?->cover) {
                Storage::disk('public')->delete($this->record->cover);
            }
        }

        if ($this->record) {
            $this->record->update($data);
        } else {
            $this->record = FinancialRecord::create($data);
        }

        $this->mode = 'show';
        $this->dispatch('swal:toast', type:'success', message:'Data disimpan.');
    }

    public function delete(): void
    {
        if (!$this->record) return;
        if ($this->record->cover) Storage::disk('public')->delete($this->record->cover);
        $this->record->delete();

        redirect()->route('app.finance');
    }
}
