<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Locker;
use App\Models\Transaction;
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
        
        return view('admin.dashboard', compact(
            'adminCount', 'lockerCount', 'lockerAvailable', 'lockerOccupied', 'lockerMaintenance',
            'transactionCount', 'transactionCompleted', 'transactionActive', 'transactionExpired', 'labels', 'data'
        ));
    }
}
