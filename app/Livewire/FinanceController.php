<?php

namespace App\Http\Controllers;

use App\Models\FinancialRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FinanceController extends Controller
{
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        // simpan cover (opsional)
        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $data['user_id'] = auth()->id();
        FinancialRecord::create($data);

        return redirect()->route('app.finance')->with('success', 'Catatan berhasil ditambahkan.');
    }

    public function update(Request $request, FinancialRecord $record)
    {
        // pastikan hanya milik user
        abort_unless($record->user_id === auth()->id(), 403);

        $data = $this->validateData($request);

        if ($request->hasFile('cover')) {
            // hapus lama jika ada
            if ($record->cover) {
                Storage::disk('public')->delete($record->cover);
            }
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $record->update($data);

        return redirect()->route('app.finance.detail', $record)->with('success', 'Catatan diperbarui.');
    }

    public function destroy(FinancialRecord $record)
    {
        abort_unless($record->user_id === auth()->id(), 403);

        if ($record->cover) {
            Storage::disk('public')->delete($record->cover);
        }
        $record->delete();

        return redirect()->route('app.finance')->with('success', 'Catatan dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'type'          => 'required|in:income,expense',
            'amount'        => 'required|numeric|min:0',
            'transacted_at' => 'required|date',
            'title'         => 'required|string|max:150',
            'category'      => 'nullable|string|max:80',
            'notes'         => 'nullable|string',
            'cover'         => 'nullable|image|max:2048', // 2MB
        ]);
    }
}
