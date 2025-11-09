<div class="space-y-4">
    @push('scripts')
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script>
        document.addEventListener('livewire:init', () => {
          Livewire.on('swal:toast', ({type, message}) => {
            Swal.fire({toast:true, position:'top-end', timer:1800, showConfirmButton:false,
              background:'#0f172a', color:'#e2e8f0', icon:type, title:message});
          });
        });
      </script>
    @endpush

    @if($mode === 'show' && $record)
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold text-slate-100">{{ $record->title }}</h2>
        <div class="space-x-3">
          <a href="{{ request()->url() }}?mode=edit" class="btn-gradient px-3 py-1.5 rounded shadow-sm">Edit</a>
          <button wire:click="delete" onclick="return confirm('Hapus data?')" class="px-3 py-1.5 rounded bg-danger text-white">Hapus</button>
        </div>
      </div>

      <div class="card-glass p-4 space-y-3">
        @if($record->cover)
          <img src="{{ asset('storage/'.$record->cover) }}" alt="cover" class="w-64 rounded-lg ring-1 ring-white/10">
        @endif
        <div><b class="text-slate-400">Tanggal:</b> {{ $record->transacted_at->format('Y-m-d') }}</div>
        <div><b class="text-slate-400">Kategori:</b> {{ $record->category }}</div>
        <div><b class="text-slate-400">Tipe:</b>
          @if($record->type=='income')
            <span class="chip chip-income">Income</span>
          @else
            <span class="chip chip-expense">Expense</span>
          @endif
        </div>
        <div><b class="text-slate-400">Jumlah:</b> {{ number_format($record->amount,2,',','.') }}</div>
        <div><b class="text-slate-400">Catatan:</b> {!! nl2br(e($record->notes)) !!}</div>
      </div>

      <a href="{{ route('app.finance') }}" class="text-emerald-400 hover:underline">← Kembali</a>

    @else
      <form wire:submit="save" class="card-glass p-4 space-y-4">
        <div class="grid md:grid-cols-2 gap-3">
          <div>
            <label class="text-sm text-slate-300">Judul</label>
            <input type="text" wire:model.defer="title"
                   class="border rounded px-3 py-2 w-full bg-slate-900 border-slate-700 text-slate-200
                          focus:ring-2 focus:ring-accent/30 focus:border-accent">
            @error('title')<div class="text-red-400 text-sm">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="text-sm text-slate-300">Tanggal</label>
            <input type="date" wire:model.defer="transacted_at"
                   class="border rounded px-3 py-2 w-full bg-slate-900 border-slate-700 text-slate-200
                          focus:ring-2 focus:ring-info/30 focus:border-info">
            @error('transacted_at')<div class="text-red-400 text-sm">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="text-sm text-slate-300">Kategori</label>
            <input type="text" wire:model.defer="category"
                   class="border rounded px-3 py-2 w-full bg-slate-900 border-slate-700 text-slate-200
                          focus:ring-2 focus:ring-primary/30 focus:border-primary">
            @error('category')<div class="text-red-400 text-sm">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="text-sm text-slate-300">Tipe</label>
            <select wire:model.defer="type"
                    class="border rounded px-3 py-2 w-full bg-slate-900 border-slate-700 text-slate-200
                           focus:ring-2 focus:ring-primary/30 focus:border-primary">
              <option value="income">Pemasukan</option>
              <option value="expense">Pengeluaran</option>
            </select>
            @error('type')<div class="text-red-400 text-sm">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="text-sm text-slate-300">Jumlah</label>
            <input type="number" step="0.01" wire:model.defer="amount"
                   class="border rounded px-3 py-2 w-full bg-slate-900 border-slate-700 text-slate-200
                          focus:ring-2 focus:ring-warning/30 focus:border-warning">
            @error('amount')<div class="text-red-400 text-sm">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="text-sm text-slate-300">Cover (gambar)</label>
            <input type="file" wire:model="cover_upload"
                   class="border rounded px-3 py-2 w-full bg-slate-900 border-slate-700 text-slate-200">
            @error('cover_upload')<div class="text-red-400 text-sm">{{ $message }}</div>@enderror
            @if($cover_upload) <div class="mt-2 text-sm text-slate-400">Preview diupload…</div> @endif
          </div>
        </div>

        <div>
          <label class="text-sm text-slate-300 block mb-1">Catatan</label>
          <textarea rows="4" wire:model.defer="notes"
                    class="border rounded px-3 py-2 w-full bg-slate-900 border-slate-700 text-slate-200
                           focus:ring-2 focus:ring-info/30 focus:border-info"></textarea>
          @error('notes')<div class="text-red-400 text-sm">{{ $message }}</div>@enderror
        </div>

        <div class="flex gap-3">
          <button class="btn-gradient px-4 py-2 rounded shadow-sm">Simpan</button>
          <a href="{{ $record? route('app.finance.detail', $record) : route('app.finance') }}" class="px-4 py-2 border border-slate-700 rounded text-slate-200">Batal</a>
        </div>
      </form>
    @endif
</div>
