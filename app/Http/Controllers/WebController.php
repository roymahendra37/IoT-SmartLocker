<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Locker;
use App\Models\Transaction;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;
use Intervention\Image\ImageManager;

class WebController extends Controller
{
// halaman home
    public function home()
    {
        return view('home');
    }

// halaman pilih loker titip
    public function titip_pilihLoker()
    {
        $lockers = Locker::where('status', 'available')->get();
        return view('titip/titip-pilih-loker', compact('lockers'));
    }

// mengirim data loker titip yang dipilih
    public function titip_pilihLokerStore(Request $request)
    {
        $request->validate([
            'locker_id' => 'required|exists:lockers,id',
        ]);

        session(['selected_locker' => $request->locker_id]);
        return redirect()->route('form.titip');
    }

// halaman pilih loker kirim
    public function kirim_pilihLoker()
    {
        $lockers = Locker::where('status', 'available')->get();
        return view('kirim/kirim-pilih-loker', compact('lockers'));
    }

// mengirim data loker kirim yang dipilih
    public function kirim_pilihLokerStore(Request $request)
    {
        $request->validate([
            'locker_id' => 'required|exists:lockers,id',
        ]);

        session(['selected_locker' => $request->locker_id]);
        return redirect()->route('form.kirim');
    }

// halaman form titip
    public function formTitip()
    {
        $lockerId = session('selected_locker');
        if (!$lockerId) {
            return redirect()->route('pilih.loker')->with('error', 'Silakan pilih loker terlebih dahulu.');
        }

        $locker = Locker::findOrFail($lockerId);
        return view('titip/titip-form', compact('locker'));
    }

// halaman form kirim
    public function formKirim()
    {
        $lockerId = session('selected_locker');
        if (!$lockerId) {
            return redirect()->route('pilih.loker')->with('error', 'Silakan pilih loker terlebih dahulu.');
        }

        $locker = Locker::findOrFail($lockerId);
        return view('kirim/kirim-form', compact('locker'));
    }

// proses titip
    public function titip(Request $request)
    {
        if (!session()->has('selected_locker') || session('selected_locker') != $request->locker_id) {
            return redirect()->route('titip.pilih.loker')->with('error', 'Silakan pilih loker terlebih dahulu.');
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'locker_id' => 'required|exists:lockers,id',
            'duration' => 'required|integer|min:1',
        ]);

        $existing = Transaction::where('email', $request->email)
            ->whereIn('status', ['active', 'expired'])
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda masih memiliki transaksi titip yang belum selesai atau expired.');
        }

        $locker = Locker::findOrFail($request->locker_id);
        if ($locker->status !== 'available') {
            return back()->with('error', 'Loker tidak tersedia.');
        }

        $qr_code = strtoupper($locker->locker_code . '-' . uniqid());
        $qr_dir = storage_path('app/public/qrcodes');
        if (!file_exists($qr_dir)) mkdir($qr_dir, 0777, true);

        $temp_qr_path = $qr_dir . '/' . $qr_code . '.png';

        // Generate QR code sementara
        QrCode::format('png')->size(300)->errorCorrection('H')->generate($qr_code, $temp_qr_path);

        // Buat background putih
        $manager = ImageManager::gd();
        $qr = $manager->read($temp_qr_path);
        $bg = $manager->create($qr->width() + 40, $qr->height() + 40, '#ffffff');
        $bg->place($qr, 'center');

        // Simpan versi akhir dengan background putih
        $final_qr_path = $qr_dir . '/' . $qr_code . '.jpg';
        $bg->save($final_qr_path);

        // Hapus QR tanpa background
        if (file_exists($temp_qr_path)) unlink($temp_qr_path);

        $start = Carbon::now('Asia/Jakarta');
        $end = $start->copy()->addHours((int)$request->duration);

        $transaction = Transaction::create([
            'locker_id' => $locker->id,
            'type' => 'titip',
            'name' => $request->name,
            'email' => $request->email,
            'duration' => (int)$request->duration,
            'qr_code' => $qr_code,
            'qr_image' => 'storage/qrcodes/' . $qr_code . '.jpg',
            'start_time' => $start,
            'end_time' => $end,
            'status' => 'active',
            'fcm_token' => $request->fcm_token,
        ]);

        $locker->update(['status' => 'occupied']);

        // Kirim email
        Mail::send('email.email-titip', [
            'transaction'   => $transaction,
            'locker'        => $locker,
            'final_qr_path' => $final_qr_path,
        ], function ($msg) use ($request) {
            $msg->to($request->email)
                ->subject('QR Code Smart Locker');
        });

        session()->forget('selected_locker');

        return view('titip/titip-success', compact('transaction', 'locker'));
    }

// proses kirim
    public function kirim(Request $request)
    {
        if (!session()->has('selected_locker') || session('selected_locker') != $request->locker_id) {
            return redirect()->route('kirim.pilih.loker')->with('error', 'Silakan pilih loker terlebih dahulu.');
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'receiver_name' => 'required|string|max:100',
            'receiver_email' => 'required|email|max:150',
            'locker_id' => 'required|exists:lockers,id',
        ]);

        $locker = Locker::find($request->locker_id);
        if ($locker->status !== 'available') {
            return back()->with('error', 'Loker tidak tersedia.');
        }

        $qr_code = strtoupper($locker->locker_code . '-' . uniqid());
        $qr_dir = storage_path('app/public/qrcodes');
        if (!file_exists($qr_dir)) mkdir($qr_dir, 0777, true);

        $temp_qr_path = $qr_dir . '/' . $qr_code . '.png';

        // Generate QR code sementara
        QrCode::format('png')->size(300)->errorCorrection('H')->generate($qr_code, $temp_qr_path);

        // Buat background putih
        $manager = ImageManager::gd();
        $qr = $manager->read($temp_qr_path);
        $bg = $manager->create($qr->width() + 40, $qr->height() + 40, '#ffffff');
        $bg->place($qr, 'center');

        // Simpan hasil akhir
        $final_qr_path = $qr_dir . '/' . $qr_code . '.jpg';
        $bg->save($final_qr_path);

        // Hapus QR tanpa background
        if (file_exists($temp_qr_path)) unlink($temp_qr_path);

        $start = Carbon::now('Asia/Jakarta');
        $end = Carbon::now('Asia/Jakarta')->addHours(48);

        $transaction = Transaction::create([
            'locker_id' => $locker->id,
            'type' => 'kirim',
            'name' => $request->name,
            'receiver_name' => $request->receiver_name,
            'receiver_email' => $request->receiver_email,
            'duration' => 48,
            'qr_code' => $qr_code,
            'qr_image' => 'storage/qrcodes/' . $qr_code . '.jpg',
            'start_time' => $start,
            'end_time' => $end,
            'status' => 'active',
        ]);

        $locker->update(['status' => 'occupied']);

        // Kirim email
        Mail::send('email.email-kirim', [
            'transaction'   => $transaction,
            'locker'        => $locker,
            'final_qr_path' => $final_qr_path,
        ], function ($msg) use ($request) {
            $msg->to($request->receiver_email)
                ->subject('QR Code Smart Locker');
        });

        session()->forget('selected_locker');

        return view('kirim/kirim-success', compact('transaction', 'locker'));
    }

}