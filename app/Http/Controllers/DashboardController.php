<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Locker;
use App\Models\Transaction;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
// 1. halaman dashboard
    public function dashboard()
    {
        $adminCount = Admin::count();

        $lockerCount = Locker::count();
        $lockerAvailable = Locker::where('status', 'available')->count();
        $lockerOccupied = Locker::where('status', 'occupied')->count();
        $lockerMaintenance = Locker::where('status', 'maintenance')->count();

        $transactionCount = Transaction::count();
        $transactionCompleted = Transaction::where('status', 'completed')->count();
        $transactionActive = Transaction::where('status', 'active')->count();
        $transactionExpired = Transaction::where('status', 'expired')->count();
        
        // Ambil data penggunaan loker per hari (7 hari terakhir)
        $dailyUsage = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();
        
        // Format data untuk Chart.js
        $labels = [];
        $data = [];
        
        foreach ($dailyUsage as $usage) {
            $labels[] = \Carbon\Carbon::parse($usage->date)->format('d M');
            $data[] = $usage->total;
        }

        // Ambil data polling ESP32 terakhir
        $espLastPoll = Setting::where('key', 'esp_last_poll')->value('value');
        
        return view('admin.dashboard', compact(
            'adminCount', 'lockerCount', 'lockerAvailable', 'lockerOccupied', 'lockerMaintenance',
            'transactionCount', 'transactionCompleted', 'transactionActive', 'transactionExpired', 'labels', 'data',
            'espLastPoll'
        ));
    }

    public function getEspStatus()
    {
        $espLastPoll = Setting::where('key', 'esp_last_poll')->value('value');

        if ($espLastPoll) {
            return response()->json([
                'status' => 'success',
                'timestamp' => \Carbon\Carbon::parse($espLastPoll)->translatedFormat('l, d F Y H:i:s'),
                'relative' => '(' . \Carbon\Carbon::parse($espLastPoll)->diffForHumans() . ')',
                'raw' => $espLastPoll
            ]);
        }

        return response()->json([
            'status' => 'empty',
            'timestamp' => 'Belum ada data',
            'relative' => '',
            'raw' => null
        ]);
    }
}
