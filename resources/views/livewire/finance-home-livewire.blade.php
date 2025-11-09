<div class="space-y-6">
    <!-- Filter Bar -->
    <div class="card-glass p-4">
      <div class="flex flex-wrap gap-3 items-end">
          <div>
              <label class="text-sm text-slate-300">Cari</label>
              <input type="text" wire:model.debounce.400ms="search"
                     class="border rounded px-3 py-2 w-60 focus:ring-2 focus:ring-info/30 focus:border-info
                            bg-slate-900 border-slate-700 text-slate-200 placeholder:text-slate-500"
                     placeholder="Judul / Kategori">
          </div>
          <div>
              <label class="text-sm text-slate-300">Tipe</label>
              <select wire:model="type"
                      class="border rounded px-3 py-2 focus:ring-2 focus:ring-accent/30 focus:border-accent
                             bg-slate-900 border-slate-700 text-slate-200">
                  <option value="">Semua</option>
                  <option value="income">Pemasukan</option>
                  <option value="expense">Pengeluaran</option>
              </select>
          </div>
          <div>
              <label class="text-sm text-slate-300">Dari</label>
              <input type="date" wire:model="date_from"
                     class="border rounded px-3 py-2 bg-slate-900 border-slate-700 text-slate-200
                            focus:ring-2 focus:ring-primary/30 focus:border-primary">
          </div>
          <div>
              <label class="text-sm text-slate-300">Sampai</label>
              <input type="date" wire:model="date_to"
                     class="border rounded px-3 py-2 bg-slate-900 border-slate-700 text-slate-200
                            focus:ring-2 focus:ring-primary/30 focus:border-primary">
          </div>

          <a href="{{ route('app.finance.create') }}"
             class="ml-auto btn-gradient px-4 py-2 rounded shadow-sm">
              <i class="bi bi-plus-lg me-1"></i> Tambah
          </a>
      </div>
    </div>

    <!-- Tabel -->
    <div class="overflow-hidden rounded-2xl ring-1 ring-white/10 bg-slate-900/70">
        <table class="w-full text-sm">
            <thead>
              <tr class="bg-slate-800 text-slate-200">
                  <th class="p-3 text-left">Tanggal</th>
                  <th class="p-3 text-left">Judul</th>
                  <th class="p-3 text-left">Kategori</th>
                  <th class="p-3 text-right">Jumlah</th>
                  <th class="p-3 text-center">Tipe</th>
                  <th class="p-3 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody class="[&>tr:nth-child(even)]:bg-slate-900/60 text-slate-200">
            @forelse($records as $r)
                <tr class="border-t border-slate-800 hover:bg-slate-800/60 transition">
                    <td class="p-3">{{ $r->transacted_at->format('Y-m-d') }}</td>
                    <td class="p-3">
                        <a class="text-emerald-400 hover:text-emerald-300 underline decoration-emerald-700/40"
                           href="{{ route('app.finance.detail', $r) }}">{{ $r->title }}</a>
                    </td>
                    <td class="p-3">{{ $r->category }}</td>
                    <td class="p-3 text-right">{{ number_format($r->amount,2,',','.') }}</td>
                    <td class="p-3 text-center">
                        @if($r->type=='income')
                          <span class="chip chip-income"><i class="bi bi-arrow-down-left"></i> Income</span>
                        @else
                          <span class="chip chip-expense"><i class="bi bi-arrow-up-right"></i> Expense</span>
                        @endif
                    </td>
                    <td class="p-3 text-center">
                        <a href="{{ route('app.finance.detail', $r) }}?mode=edit" class="text-sky-400 hover:underline">Edit</a>
                        <span class="px-1 text-slate-500">•</span>
                        <button wire:click="askDelete({{ $r->id }})" class="text-rose-400 hover:underline">Hapus</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="p-6 text-center text-slate-400">Belum ada data</td></tr>
            @endforelse
            </tbody>
        </table>

        <div class="p-3 border-t border-slate-800 bg-slate-900/70">
            {{ $records->links() }}
        </div>
    </div>

    @push('scripts')
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script>
        document.addEventListener('livewire:init', () => {
          Livewire.on('swal:confirm-delete', () => {
            Swal.fire({
              title: 'Hapus data?',
              icon: 'warning',
              background:'#0f172a', color:'#e2e8f0',
              showCancelButton: true,
              confirmButtonText: 'Ya, hapus',
              cancelButtonText: 'Batal'
            }).then((r) => { if (r.isConfirmed) Livewire.dispatch('swalConfirmedDelete') })
          });

          Livewire.on('swal:toast', ({type, message}) => {
            Swal.fire({toast:true, position:'top-end', timer:1800, showConfirmButton:false,
              background:'#0f172a', color:'#e2e8f0', icon:type, title:message});
          });
        });
      </script>
    @endpush
</div>
