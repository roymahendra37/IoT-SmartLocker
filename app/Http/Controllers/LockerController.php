<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Locker;

class LockerController extends Controller
{
// 1. halaman admin daftar loker
    public function daftarLoker()
    {
        $lockers = Locker::orderBy('id')->get();
        return view('admin.daftar-loker', compact('lockers'));
    }

// 2. halaman admin tambah loker
    public function tambahLoker()
    {
        return view('admin.tambah-loker');
    }

// 3. menambahkan data loker ke db
    public function storeLoker(Request $request)
    {
        $request->validate([
            'locker_number' => 'required|unique:lockers,locker_number',
            'locker_code' => 'required|unique:lockers,locker_code',
        ]);

        Locker::create([
            'locker_number' => $request->locker_number,
            'locker_code' => strtoupper($request->locker_code),
            'status' => 'available',
        ]);

        return redirect()->route('lockers.index')->with('success', 'Loker berhasil ditambahkan!');
    }

// 4. halaman admin edit loker
    public function editLoker($id)
    {
        $locker = Locker::findOrFail($id);
        return view('admin.edit-loker', compact('locker'));
    }

// 5. update data loker di db
    public function updateLoker(Request $request, $id)
    {
        $locker = Locker::findOrFail($id);

        $request->validate([
            'locker_number' => 'required|numeric|unique:lockers,locker_number,' . $locker->id,
            'locker_code' => 'required|string|unique:lockers,locker_code,' . $locker->id,
        ]);

        $locker->update([
            'locker_number' => $request->locker_number,
            'locker_code' => $request->locker_code,
        ]);

        return redirect()->route('lockers.index')->with('success', 'Loker berhasil diperbarui!');
    }

// 6. hapus data loker di db
    public function destroyLoker($id)
    {
        $locker = Locker::findOrFail($id);
        $locker->delete();

        return redirect()->route('lockers.index')->with('success', 'Loker berhasil dihapus!');
    }
// 7. set status
    public function toggleStatus($id)
    {
        $locker = Locker::findOrFail($id);

        if ($locker->status === 'available') {
            $locker->status = 'maintenance';
            $locker->save();
            return redirect()->route('lockers.index')->with('success', 'Status loker berhasil diubah menjadi MAINTENANCE!');
        } elseif ($locker->status === 'maintenance') {
            $locker->status = 'available';
            $locker->save();
            return redirect()->route('lockers.index')->with('success', 'Status loker berhasil diubah menjadi TERSEDIA!');
        }

        return redirect()->route('lockers.index')->with('error', 'Status hanya bisa diubah jika loker tersedia atau maintenance!');
    }

}
