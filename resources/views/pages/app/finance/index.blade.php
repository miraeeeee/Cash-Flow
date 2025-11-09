@extends('layouts.app')

@section('title','Catatan Keuangan')

@section('content')
<style>
  .wrap {padding: 1.75rem}
  .toolbar {display:flex; gap:.75rem; align-items:center; justify-content:flex-end}
  .btn {background:#2563eb; color:#fff; border:none; border-radius:.6rem; padding:.6rem .9rem; font-weight:600}
  .btn:hover {background:#1d4ed8}
  .btn-ghost {background:#eef2ff; color:#3730a3}
  .btn-outline {background:#fff; color:#2563eb; border:1px solid #dbeafe}
  .grid {display:grid; gap:.75rem}
  .filters {grid-template-columns: 1fr 220px 200px 200px}
  .card {background:#fff;border-radius:14px; box-shadow:0 4px 12px rgba(0,0,0,.06)}
  .card-body {padding:1rem}
  .summary {display:grid; grid-template-columns:repeat(3,1fr); gap:.75rem}
  .sum-card h5 {font-size:.85rem; color:#6b7280; margin:0 0 .35rem}
  .sum-card h2 {font-size:1.2rem; margin:0; color:#111827}
  .sum-in {color:#059669}
  .sum-out {color:#dc2626}
  table{width:100%; border-collapse:collapse}
  th{background:#f3f4f6; text-align:left; padding:.8rem; font-size:.85rem; color:#6b7280}
  td{padding:.85rem; border-bottom:1px solid #eef2f7}
  tr:hover td{background:#fafbff}
  .badge{padding:.25rem .5rem; border-radius:.6rem; font-size:.75rem; font-weight:700}
  .badge-in{background:#e8fff4; color:#047857}
  .badge-out{background:#ffecec; color:#b91c1c}
  .table-card{margin-top:1rem}
  .muted{color:#6b7280}
  .search-btn{grid-column:1 / -1}
  @media (max-width: 1024px){
    .filters {grid-template-columns: 1fr 1fr}
    .summary {grid-template-columns: 1fr}
  }
</style>

<div class="wrap">
  <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.75rem">
    <h1 style="font-size:24px; margin:0; color:#111827">Catatan Keuangan</h1>
    <div class="toolbar">
      <a href="{{ route('app.finance.create') }}" class="btn">+ Tambah</a>
      <a href="{{ route('app.finance') }}" class="btn btn-outline">Reset</a>
      <button class="btn btn-outline" id="btnExport">Export CSV</button>
      <button class="btn btn-ghost" onclick="window.print()">Print</button>
    </div>
  </div>

  {{-- ===================== FILTERS ===================== --}}
  <div class="card">
    <div class="card-body grid filters">
      <form id="filterForm" action="{{ route('app.finance') }}" method="get" class="grid filters" style="gap:.75rem">
        <input type="text" name="search" value="{{ request('search') }}" class="card" style="padding:.7rem .9rem"
               placeholder="Judul / Kategori">
        <select name="type" class="card" style="padding:.7rem .9rem">
          @php $sel = request('type'); @endphp
          <option value="">Semua</option>
          <option value="income"  {{ $sel==='income'?'selected':'' }}>Pemasukan</option>
          <option value="expense" {{ $sel==='expense'?'selected':'' }}>Pengeluaran</option>
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="card" style="padding:.7rem .9rem">
        <input type="date" name="date_to"   value="{{ request('date_to') }}"   class="card" style="padding:.7rem .9rem">
        <button class="btn search-btn">Cari</button>
      </form>
    </div>
  </div>

  {{-- ===================== SUMMARY + CHART ===================== --}}
  @php
    // fallback kalau controller belum mengirim sum khusus; pakai data halaman aktif
    $collection   = isset($records) ? (method_exists($records,'getCollection') ? $records->getCollection() : collect($records)) : collect();
    $sumIncome    = isset($sumIncome)    ? $sumIncome    : $collection->where('type','income')->sum('amount');
    $sumExpense   = isset($sumExpense)   ? $sumExpense   : $collection->where('type','expense')->sum('amount');
    $sumBalance   = isset($sumBalance)   ? $sumBalance   : ($sumIncome - $sumExpense);
  @endphp

  <div class="grid" style="margin-top:1rem; grid-template-columns: 1.3fr .7fr">
    <div class="card">
      <div class="card-body">
        <div class="summary">
          <div class="sum-card">
            <h5>Saldo</h5>
            <h2>Rp {{ number_format($sumBalance,0,',','.') }}</h2>
          </div>
          <div class="sum-card">
            <h5>Pemasukan</h5>
            <h2 class="sum-in">Rp {{ number_format($sumIncome,0,',','.') }}</h2>
          </div>
          <div class="sum-card">
            <h5>Pengeluaran</h5>
            <h2 class="sum-out">Rp {{ number_format($sumExpense,0,',','.') }}</h2>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="muted" style="margin-bottom:.4rem; font-weight:600">Komposisi</div>
        <div id="donutChart"></div>
      </div>
    </div>
  </div>

  {{-- ===================== TABLE ===================== --}}
  <div class="card table-card">
    <div class="card-body" style="padding:0">
      <table id="financeTable">
        <thead>
          <tr>
            <th style="width:140px">Tanggal</th>
            <th>Judul</th>
            <th style="width:220px">Kategori</th>
            <th style="width:160px">Jumlah</th>
            <th style="width:120px">Tipe</th>
            <th style="width:120px; text-align:right">Aksi</th>
          </tr>
        </thead>
        <tbody>
        @forelse ($records as $r)
          <tr>
            <td>{{ \Carbon\Carbon::parse($r->transacted_at)->format('Y-m-d') }}</td>
            <td><a href="{{ route('app.finance.detail',$r->id) }}">{{ $r->title }}</a></td>
            <td>{{ $r->category ?? '-' }}</td>
            <td>Rp {{ number_format($r->amount,0,',','.') }}</td>
            <td>
              @if($r->type==='income')
                <span class="badge badge-in">Income</span>
              @else
                <span class="badge badge-out">Expense</span>
              @endif
            </td>
            <td style="text-align:right">
              <a href="{{ route('app.finance.detail',$r->id) }}" class="muted">Edit</a>
              &nbsp;·&nbsp;
              <form action="{{ route('app.finance.destroy',$r->id) }}" method="post" style="display:inline"
                    onsubmit="return confirm('Hapus catatan ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="muted" style="color:#ef4444; background:none; border:none; cursor:pointer">
                  Hapus
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" style="text-align:center; padding:2rem" class="muted">
            Belum ada data untuk filter saat ini.
          </td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if(method_exists($records,'links'))
    <div style="margin-top:1rem">
      {{ $records->onEachSide(1)->links() }}
    </div>
  @endif
</div>

{{-- ============= CHART + EXPORT ============= --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
  // Donut chart
  const donut = new ApexCharts(document.querySelector("#donutChart"), {
    chart: { type: 'donut', height: 240 },
    labels: ['Pemasukan','Pengeluaran'],
    series: [{{ (float)$sumIncome }}, {{ (float)$sumExpense }}],
    colors: ['#2563eb','#ef4444'],
    legend: { position:'bottom' },
    dataLabels: { formatter: val => `${val.toFixed(1)}%` }
  });
  donut.render();

  // Export CSV sederhana dari tabel tampak
  document.getElementById('btnExport').addEventListener('click', () => {
    const rows = [...document.querySelectorAll('#financeTable tr')]
      .map(tr => [...tr.querySelectorAll('th,td')].map(td => {
        let t = td.innerText.replace(/\s+/g,' ').trim();
        return `"${t.replaceAll('"','""')}"`;
      }).join(',')).join('\n');

    const blob = new Blob([rows], {type:'text/csv;charset=utf-8;'});
    const url  = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url; a.download = 'catatan-keuangan.csv'; a.click();
    URL.revokeObjectURL(url);
  });
</script>
@endsection
