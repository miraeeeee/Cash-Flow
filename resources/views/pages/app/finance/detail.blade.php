@extends('layouts.app', ['title' => isset($record) ? 'Ubah Catatan' : 'Tambah Catatan'])

@section('content')
@php
    $isEdit = isset($record) && $record;
    $coverPath = $isEdit ? ($record->cover ?? $record->cover_path ?? null) : null;

    // normalisasi tanggal agar aman
    $transacted = old('transacted_at');
    if (!$transacted && $isEdit && !empty($record->transacted_at)) {
        try {
            $transacted = \Illuminate\Support\Carbon::parse($record->transacted_at)->format('Y-m-d');
        } catch (\Throwable $e) {
            $transacted = '';
        }
    }
@endphp

<div class="grid gap-6">
  <!-- Kartu utama: gunakan card-glass agar kontras di dark mode -->
  <div class="card-glass rounded-2xl p-6">
    <h1 class="text-2xl font-semibold mb-6 text-slate-100">
      {{ $isEdit ? 'Ubah Catatan' : 'Tambah Catatan' }}
    </h1>

    {{-- FORM SIMPAN (CREATE / UPDATE) --}}
    <form method="POST"
          action="{{ $isEdit ? route('app.finance.update', $record->id) : route('app.finance.store') }}"
          enctype="multipart/form-data"
          class="grid gap-4">
      @csrf
      @if($isEdit) @method('PUT') @endif

      <div class="grid sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm mb-1 text-slate-300">Jenis</label>
          <select name="type"
                  class="w-full rounded-lg px-3 py-2 border bg-slate-900 border-slate-700 text-slate-200
                         focus:ring-2 focus:ring-primary/30 focus:border-primary"
                  required>
            <option value="income"  @selected(old('type', $isEdit ? $record->type : '')==='income')>Pemasukan</option>
            <option value="expense" @selected(old('type', $isEdit ? $record->type : '')==='expense')>Pengeluaran</option>
          </select>
        </div>

        <div>
          <label class="block text-sm mb-1 text-slate-300">Jumlah (Rp)</label>
          <input type="number" step="0.01" name="amount" required
                 value="{{ old('amount', $isEdit ? $record->amount : '') }}"
                 class="w-full rounded-lg px-3 py-2 border bg-slate-900 border-slate-700 text-slate-200 placeholder:text-slate-500
                        focus:ring-2 focus:ring-primary/30 focus:border-primary">
        </div>
      </div>

      <div class="grid sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm mb-1 text-slate-300">Judul</label>
          <input type="text" name="title" required
                 value="{{ old('title', $isEdit ? $record->title : '') }}"
                 class="w-full rounded-lg px-3 py-2 border bg-slate-900 border-slate-700 text-slate-200 placeholder:text-slate-500
                        focus:ring-2 focus:ring-info/30 focus:border-info">
        </div>
        <div>
          <label class="block text-sm mb-1 text-slate-300">Kategori</label>
          <input type="text" name="category"
                 value="{{ old('category', $isEdit ? ($record->category ?? '') : '') }}"
                 class="w-full rounded-lg px-3 py-2 border bg-slate-900 border-slate-700 text-slate-200 placeholder:text-slate-500
                        focus:ring-2 focus:ring-accent/30 focus:border-accent">
        </div>
      </div>

      <div class="grid sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm mb-1 text-slate-300">Tanggal</label>
          <input type="date" name="transacted_at"
                 value="{{ old('transacted_at', $transacted) }}"
                 class="w-full rounded-lg px-3 py-2 border bg-slate-900 border-slate-700 text-slate-200
                        focus:ring-2 focus:ring-info/30 focus:border-info">
        </div>

        <div>
          <label class="block text-sm mb-1 text-slate-300">Catatan</label>
          <textarea name="notes" rows="3"
                    class="w-full rounded-lg px-3 py-2 border bg-slate-900 border-slate-700 text-slate-200 placeholder:text-slate-500
                           focus:ring-2 focus:ring-info/30 focus:border-info">{{ old('notes', $isEdit ? ($record->notes ?? '') : '') }}</textarea>
        </div>
      </div>

      <div>
        <label class="block text-sm mb-1 text-slate-300">Cover (opsional)</label>
        <input type="file" name="cover" accept="image/*"
               class="w-full rounded-lg px-3 py-2 border bg-slate-900 border-slate-700 text-slate-200">
        @if($coverPath)
          <img src="{{ asset('storage/'.$coverPath) }}" alt="cover"
               class="h-24 mt-3 rounded-lg border border-slate-700 ring-1 ring-white/10 object-cover">
        @endif
      </div>

      <div class="flex items-center gap-3 pt-2">
        <button type="submit"
                class="btn-gradient px-5 py-2.5 rounded-lg shadow-sm">
          {{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}
        </button>

        <a href="{{ $isEdit ? route('app.finance.detail', $record) : route('app.finance') }}"
           class="px-4 py-2 rounded-lg border border-slate-700 text-slate-200 hover:bg-white/5">
          Batal
        </a>

        @if($isEdit)
          <button form="delete-record-form" type="submit"
                  class="ml-auto px-3 py-2 rounded-lg text-rose-400 hover:bg-rose-500/10">
            Hapus
          </button>
        @endif
      </div>
    </form>
  </div>

  {{-- FORM HAPUS TERPISAH (tidak bersarang) --}}
  @if($isEdit)
    <form id="delete-record-form"
          action="{{ route('app.finance.destroy', $record->id) }}"
          method="POST"
          onsubmit="return confirm('Hapus catatan ini?')">
      @csrf
      @method('DELETE')
    </form>
  @endif
</div>
@endsection
