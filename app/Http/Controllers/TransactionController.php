<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;

class TransactionController extends Controller
{
// 1. halaman admin daftar transaksi
    public function daftarTransaksi(Request $request)
    {
        $status = $request->query('status');
        $query = Transaction::orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        $transactions = $query->get();
        return view('admin.daftar-transaksi', compact('transactions', 'status'));
    }

// 2. halaman admin detail transaksi
    public function showtransaksi($id)
    {
        $transaction = Transaction::with('locker')->findOrFail($id);
        return view('admin.detail-transaksi', compact('transaction'));
    }

// 3. buka loker saat expired
    public function bukaLoker($id)
    {
        $transaction = Transaction::findOrFail($id);
        $locker = $transaction->locker;
        $lockerCode = $locker->locker_code;
        $lockerNumber = $locker->locker_number;

        if (!$lockerCode) {
            return redirect()->route('transactions.index')
                ->with('error', 'Locker tidak ditemukan.');
        }

        // Pastikan hanya bisa buka loker kalau status expired
        if ($transaction->status !== 'expired') {
            return redirect()->route('transactions.index')
                ->with('error', 'Transaksi belum expired.');
        }

        // Update status
        $transaction->update(['status' => 'completed']);
        $locker->update(['status' => 'available']);

        // Simulasi buka loker sementara (2 detik)
        Cache::put('lock_status', $lockerCode, now()->addSeconds(3));

        // Redirect balik ke daftar transaksi dengan pesan sukses
        return redirect()->route('transactions.index')->with('success', "Loker No. {$lockerNumber} berhasil dibuka!");
    }

// 4. ubah status menjadi Expired
    public function ubahExpired($id)
    {
        $transaction = Transaction::findOrFail($id);
        $locker = $transaction->locker;

        $transaction->update(['status' => 'expired']);
        $locker->update(['status' => 'occupied']);

        return redirect()->route('transactions.index')->with('success', 'Status transaksi diubah menjadi expired!');
    }
}
