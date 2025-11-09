@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<style>
  body {
      background-color: #f9fafb;
      font-family: 'Inter', sans-serif;
  }

  .dashboard-container {
      padding: 2rem;
  }

  .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
  }

  .card {
      background: #fff;
      border-radius: 12px;
      padding: 1.25rem;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      transition: 0.2s ease;
  }

  .card:hover {
      transform: translateY(-3px);
  }

  .card h4 {
      font-size: 14px;
      color: #6b7280;
      margin-bottom: 8px;
  }

  .card h2 {
      color: #2563eb;
      margin: 0;
      font-size: 20px;
      font-weight: 700;
  }

  .chart-card {
      background: #fff;
      border-radius: 12px;
      padding: 1rem 1.5rem;
      margin-bottom: 2rem;
      box-shadow: 0 4px 12px rgba(0,0,0,0.06);
  }

  .chart-card h3 {
      font-size: 16px;
      margin-bottom: 0.5rem;
      color: #111827;
      font-weight: 600;
  }

  table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      border-radius: 12px;
      overflow: hidden;
  }

  th {
      text-align: left;
      background: #f3f4f6;
      padding: 10px;
      font-size: 13px;
      color: #6b7280;
  }

  td {
      padding: 10px;
      border-bottom: 1px solid #e5e7eb;
  }

  .income {
      color: #10b981;
      font-weight: 600;
  }

  .expense {
      color: #ef4444;
      font-weight: 600;
  }

  .btn {
      display: inline-block;
      background: #2563eb;
      color: white;
      border: none;
      padding: 10px 16px;
      border-radius: 8px;
      font-weight: 600;
      text-decoration: none;
  }

  .btn:hover {
      background: #1d4ed8;
  }
</style>

<div class="dashboard-container">
  <h1 style="margin-bottom: 1rem; font-size: 24px; color: #111827;">Beranda</h1>

  <div class="cards">
      <div class="card">
          <h4>Saldo Total</h4>
          <h2>Rp {{ number_format($balance, 0, ',', '.') }}</h2>
      </div>
      <div class="card">
          <h4>Total Pemasukan</h4>
          <h2>Rp {{ number_format($totalIncome, 0, ',', '.') }}</h2>
      </div>
      <div class="card">
          <h4>Total Pengeluaran</h4>
          <h2>Rp {{ number_format($totalExpense, 0, ',', '.') }}</h2>
      </div>
      <div class="card">
          <h4>Transaksi Terakhir</h4>
          <h2>{{ count($recentRecords) }}</h2>
      </div>
  </div>

  <div class="chart-card">
      <h3>Statistik 6 Bulan Terakhir</h3>
      <div id="chart"></div>
  </div>

  <h3 style="margin-bottom: 0.75rem;">Transaksi Terbaru</h3>
  <table>
      <thead>
          <tr>
              <th>Tanggal</th>
              <th>Judul</th>
              <th>Kategori</th>
              <th>Jumlah</th>
              <th>Tipe</th>
          </tr>
      </thead>
      <tbody>
          @foreach ($recentRecords as $rec)
              <tr>
                  <td>{{ \Carbon\Carbon::parse($rec->transacted_at)->format('d M Y') }}</td>
                  <td>{{ $rec->title }}</td>
                  <td>{{ $rec->category ?? '-' }}</td>
                  <td>Rp {{ number_format($rec->amount, 0, ',', '.') }}</td>
                  <td class="{{ $rec->type === 'income' ? 'income' : 'expense' }}">
                      {{ ucfirst($rec->type) }}
                  </td>
              </tr>
          @endforeach
      </tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
  const options = {
      chart: {
          type: 'area',
          height: 320,
          toolbar: { show: false }
      },
      colors: ['#2563eb', '#ef4444'],
      dataLabels: { enabled: false },
      stroke: { curve: 'smooth', width: 3 },
      series: [
          { name: 'Pemasukan', data: @json($incomeSeries) },
          { name: 'Pengeluaran', data: @json($expenseSeries) }
      ],
      xaxis: {
          categories: @json($months),
          labels: { style: { colors: '#6b7280' } }
      },
      yaxis: {
          labels: {
              formatter: val => new Intl.NumberFormat('id-ID').format(val)
          }
      },
      legend: {
          position: 'top',
          horizontalAlign: 'left'
      },
      fill: {
          type: 'gradient',
          gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1, stops: [0, 90, 100] }
      },
      grid: {
          borderColor: '#e5e7eb'
      }
  };

  new ApexCharts(document.querySelector("#chart"), options).render();
</script>
@endsection
