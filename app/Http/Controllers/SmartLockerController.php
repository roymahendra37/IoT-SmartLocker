<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Transaction;
use App\Models\Setting;

class SmartLockerController extends Controller
{
    // proses scan dari esp32cam
    public function scan(Request $request)
    {
        $qr = $request->input('qr_code');

        $transaction = Transaction::where('qr_code', $qr)->first();
        if (!$transaction) {
            return response()->json(['error' => 'QR tidak valid'], 400);
        }

        if ($transaction->status !== 'active') {
            return response()->json(['error' => 'Transaksi sudah selesai'], 400);
        }

        $locker = $transaction->locker;
        $lockerCode = optional($locker)->locker_code;
        if (!$lockerCode) {
            return response()->json(['error' => 'Locker tidak ditemukan'], 400);
        }

        // Cek durasi
        if (now()->gt($transaction->end_time)) {
            // Kalau sudah habis waktunya
            if ($transaction->type === 'kirim' && $transaction->qr_usage_count < 2) {
                $transaction->update(['status' => 'expired']);
                $locker->update(['status' => 'occupied']);
            } else {
                $transaction->update(['status' => 'completed']);
                $locker->update(['status' => 'available']);
            }

            return response()->json(['error' => 'QR sudah kadaluarsa'], 400);
        }

        Cache::put('lock_status', $lockerCode, now()->addSeconds(3));

        // Type = titip
        if ($transaction->type === 'titip') {
            $transaction->increment('qr_usage_count');
            return response()->json([
                'message' => "Loker {$lockerCode} dibuka (titip).",
                'locker_code' => $lockerCode
            ]);
        }

        // Type = kirim
        if ($transaction->type === 'kirim') {
            $transaction->increment('qr_usage_count');

            if ($transaction->qr_usage_count >= 2) {
                $transaction->update(['status' => 'completed']);
                $locker->update(['status' => 'available']);
            }

            return response()->json([
                'message' => "Loker {$lockerCode} dibuka (kirim).",
                'locker_code' => $lockerCode
            ]);
        }

        return response()->json(['error' => 'Tipe transaksi tidak valid'], 400);
    }

    // proses membuka lock di esp32
    public function lockStatus()
    {
        // 1. Catat heartbeat ESP
        Setting::updateOrCreate(
            ['key' => 'esp_last_poll'],
            ['value' => now('Asia/Jakarta')->toDateTimeString()]
        );

        // 2. Reset esp_notified jika ESP hidup kembali (hanya titip & active)
        Transaction::where('status', 'active')
            ->where('type', 'titip')
            ->where('esp_notified', true)
            ->update(['esp_notified' => false]);

        // 3. Fungsi utama: command buka loker
        $lockerCode = Cache::pull('lock_status');

        if ($lockerCode) {
            return response()->json([
                'status' => 'on',
                'locker' => $lockerCode
            ]);
        }

        return response()->json([
            'status' => 'off',
            'locker' => null
        ]);
    }

}