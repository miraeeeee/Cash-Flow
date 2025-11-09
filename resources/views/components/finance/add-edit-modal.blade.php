@props([
    'editId' => null,
    'title' => '',
    'formType' => 'expense',
    'amount' => '',
    'transacted_at' => '',
    'notes' => null,
    'formCategory' => null,
])

<div x-data="{ open: false }"
     x-on:openModal.window="if($event.detail.id==='financeAddModal'){ open = true }"
     x-on:closeModal.window="if($event.detail.id==='financeAddModal'){ open = false }">

    <button class="hidden" x-show="false"></button>

    <div class="modal" :class="{ 'modal-open': open }">
        <div class="modal-box max-w-2xl">
            <h3 class="font-bold text-lg">{{ $editId ? 'Ubah Data' : 'Tambah Data' }}</h3>

            <form wire:submit.prevent="save" class="mt-4 space-y-3">
                <div class="grid md:grid-cols-2 gap-3">
                    <div>
                        <label class="label-text">Judul</label>
                        <input type="text" class="input input-bordered w-full" wire:model.defer="title">
                        @error('title') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="label-text">Tipe</label>
                        <select class="select select-bordered w-full" wire:model.defer="formType">
                            <option value="income">Pemasukan</option>
                            <option value="expense">Pengeluaran</option>
                        </select>
                        @error('formType') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-3">
                    <div class="md:col-span-2">
                        <label class="label-text">Jumlah (Rp)</label>
                        <input type="number" step="0.01" class="input input-bordered w-full" wire:model.defer="amount">
                        @error('amount') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="label-text">Tanggal</label>
                        <input type="date" class="input input-bordered w-full" wire:model.defer="transacted_at">
                        @error('transacted_at') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-3">
                    <div>
                        <label class="label-text">Kategori (opsional)</label>
                        <input type="text" class="input input-bordered w-full" wire:model.defer="formCategory">
                        @error('formCategory') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="label-text">Bukti/Cover (opsional)</label>
                        <input type="file" class="file-input file-input-bordered w-full" wire:model="coverFile" accept="image/*">
                        @error('coverFile') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="label-text">Catatan</label>
                    <textarea class="textarea textarea-bordered w-full" rows="3" wire:model.defer="notes"></textarea>
                    @error('notes') <span class="text-error text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="modal-action">
                    <button type="button" class="btn" @click="open=false">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
