<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    // Seller: Ajukan Penarikan Dana
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'amount' => 'required|numeric|min:10000',
        ]);

        // Cek saldo cukup
        if ($user->saldo < $request->amount) {
            return redirect()->back()->with('error', 'Saldo tidak mencukupi untuk penarikan ini.');
        }

        // Cek rekening
        if (!$user->bank_name || !$user->no_rekening || !$user->atas_nama) {
            return redirect()->back()->with('error', 'Harap lengkapi data rekening bank Anda di menu profil terlebih dahulu sebelum menarik saldo.');
        }

        try {
            DB::transaction(function () use ($request, $user) {
                // Buat record withdrawal
                Withdrawal::create([
                    'user_id' => $user->id,
                    'amount' => $request->amount,
                    'status' => 'pending',
                    'bank_name' => $user->bank_name,
                    'account_number' => $user->no_rekening,
                    'account_name' => $user->atas_nama,
                ]);

                // Kurangi saldo
                $user->decrement('saldo', $request->amount);
            });

            return redirect()->back()->with('success', 'Permintaan penarikan saldo berhasil diajukan! Admin akan segera memproses dana Anda.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // Admin: Setujui Penarikan
    public function approve(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);

        $withdrawal = Withdrawal::findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return redirect()->back()->with('error', 'Penarikan ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $namaFile = null;
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $namaFile = 'wd_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/bukti_withdrawal'), $namaFile);
        }

        $withdrawal->update([
            'status' => 'completed',
            'bukti_transfer' => $namaFile,
            'admin_note' => $request->admin_note,
        ]);

        return redirect()->back()->with('success', 'Penarikan dana berhasil disetujui dan ditandai selesai! Saldo telah diteruskan ke rekening seller.');
    }

    // Admin: Tolak Penarikan
    public function reject(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin')
            abort(403);

        $withdrawal = Withdrawal::findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return redirect()->back()->with('error', 'Penarikan ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'admin_note' => 'required|string|max:1000',
        ]);

        DB::transaction(function () use ($withdrawal, $request) {
            $withdrawal->update([
                'status' => 'rejected',
                'admin_note' => $request->admin_note,
            ]);

            // Kembalikan saldo
            $withdrawal->user->increment('saldo', $withdrawal->amount);
        });

        return redirect()->back()->with('success', 'Penarikan dana ditolak. Saldo dikembalikan ke akun Seller.');
    }
}
