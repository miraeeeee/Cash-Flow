<?php

namespace App\Http\Controllers;

use App\Models\FinancialRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FinanceController extends Controller
{
    /** Tambah data baru */
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $data['user_id'] = auth()->id();
        FinancialRecord::create($data);

        // ⬅️ selesai simpan → kembali ke daftar
        return redirect()
            ->route('app.finance')
            ->with('success', 'Catatan berhasil ditambahkan.');
    }

    /** Update data yang ada */
    public function update(Request $request, FinancialRecord $record)
    {
        abort_unless($record->user_id === auth()->id(), 403);

        $data = $this->validateData($request);

        if ($request->hasFile('cover')) {
            // hapus cover lama kalau ada
            if ($record->cover && Storage::disk('public')->exists($record->cover)) {
                Storage::disk('public')->delete($record->cover);
            }
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $record->update($data);

        // ⬅️ selesai update → kembali ke daftar (bukan ke detail)
        return redirect()
            ->route('app.finance')
            ->with('success', 'Catatan diperbarui.');
    }

    /** Hapus data */
    public function destroy(FinancialRecord $record)
    {
        abort_unless($record->user_id === auth()->id(), 403);

        if ($record->cover && Storage::disk('public')->exists($record->cover)) {
            Storage::disk('public')->delete($record->cover);
        }

        $record->delete();

        return redirect()
            ->route('app.finance')
            ->with('success', 'Catatan dihapus.');
    }

    /** Validasi input */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'type'          => 'required|in:income,expense',
            'amount'        => 'required|numeric|min:0',
            'transacted_at' => 'required|date',
            'title'         => 'required|string|max:150',
            'category'      => 'nullable|string|max:80',
            'notes'         => 'nullable|string',
            // gunakan image + batas 2MB; boleh tambah mimes jika perlu
            'cover'         => 'nullable|image|max:2048',
        ]);
    }
}
