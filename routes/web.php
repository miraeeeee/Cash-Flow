<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; // ✅ WAJIB: pakai Request yang benar
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FinanceController;
use App\Models\FinancialRecord;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {
    // Tampil form
    Route::get('/login',    [AuthController::class, 'login'])->name('auth.login');
    Route::get('/register', [AuthController::class, 'register'])->name('auth.register');

    // Submit form
    Route::post('/login',    [AuthController::class, 'authenticate'])->name('auth.login.submit');
    Route::post('/register', [AuthController::class, 'store'])->name('auth.register.submit');

    // Logout (biarkan GET seperti punyamu)
    Route::get('/logout',   [AuthController::class, 'logout'])->name('auth.logout');
});

/*
|--------------------------------------------------------------------------
| App Routes (protected)
|--------------------------------------------------------------------------
*/
Route::prefix('app')->middleware('check.auth')->group(function () {
    // Home
    Route::get('/home', [HomeController::class, 'index'])->name('app.home');

    /**
     * INDEX / LIST — kirim $records ke view (JANGAN pakai Route::view)
     * Pastikan tidak ada route lain dengan nama "app.finance".
     */
    Route::get('/finance', function (Request $req) {
        $q = FinancialRecord::query()->where('user_id', auth()->id());

        // ---- Filter opsional ----
        // Search (title/category) – PostgreSQL gunakan ILIKE
        $s = trim((string) $req->input('search', ''));
        if ($s !== '') {
            $q->where(function ($qq) use ($s) {
                $qq->where('title', 'ilike', "%{$s}%")
                   ->orWhere('category', 'ilike', "%{$s}%");
            });
        }

        // Type (income/expense)
        $t = trim((string) $req->input('type', ''));
        if ($t !== '') {
            $q->where('type', $t);
        }

        // Date range
        $from = $req->input('date_from'); // yyyy-mm-dd
        if (!empty($from)) {
            $q->whereDate('transacted_at', '>=', $from);
        }
        $to = $req->input('date_to'); // yyyy-mm-dd
        if (!empty($to)) {
            $q->whereDate('transacted_at', '<=', $to);
        }

        $records = $q->orderByDesc('transacted_at')
                     ->orderByDesc('id')
                     ->paginate(20)
                     ->withQueryString();

        return view('pages.app.finance.index', compact('records'));
    })->name('app.finance');

    // CREATE — letakkan SEBELUM /finance/{record} agar tidak "ketelan"
    Route::get('/finance/create', function () {
        return view('pages.app.finance.detail', ['record' => null]);
    })->name('app.finance.create');

    // SHOW/EDIT — hanya angka
    Route::get('/finance/{record}', function (FinancialRecord $record) {
        return view('pages.app.finance.detail', compact('record'));
    })->whereNumber('record')->name('app.finance.detail');

    // ACTIONS (CRUD) — Controller
    Route::post('/finance',            [FinanceController::class, 'store'])->name('app.finance.store');
    Route::put('/finance/{record}',    [FinanceController::class, 'update'])->whereNumber('record')->name('app.finance.update');
    Route::delete('/finance/{record}', [FinanceController::class, 'destroy'])->whereNumber('record')->name('app.finance.destroy');
});

/*
|--------------------------------------------------------------------------
| Root redirect
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('app.home'));
