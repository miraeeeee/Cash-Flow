<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\FinancialRecord;
use Carbon\CarbonPeriod;

class HomeController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // --- Kartu ringkasan ---
        $totalIncome  = (float) FinancialRecord::where('user_id', $userId)
            ->where('type', 'income')->sum('amount');
        $totalExpense = (float) FinancialRecord::where('user_id', $userId)
            ->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // --- Statistik 6 bulan terakhir ---
        $months = [];
        $incomeSeries = [];
        $expenseSeries = [];

        // dari 5 bulan lalu sampai bulan ini
        $period = CarbonPeriod::create(now()->startOfMonth()->subMonths(5), '1 month', now()->startOfMonth());

        foreach ($period as $month) {
            $months[] = $month->format('Y-m');

            $incomeSeries[] = (float) FinancialRecord::where('user_id', $userId)
                ->where('type', 'income')
                ->whereYear('transacted_at', $month->year)
                ->whereMonth('transacted_at', $month->month)
                ->sum('amount');

            $expenseSeries[] = (float) FinancialRecord::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereYear('transacted_at', $month->year)
                ->whereMonth('transacted_at', $month->month)
                ->sum('amount');
        }

        // --- Transaksi terbaru ---
        $recentRecords = FinancialRecord::where('user_id', $userId)
            ->orderByDesc('transacted_at')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return view('pages.app.home', compact(
            'balance', 'totalIncome', 'totalExpense',
            'months', 'incomeSeries', 'expenseSeries',
            'recentRecords'
        ));
    }
}
