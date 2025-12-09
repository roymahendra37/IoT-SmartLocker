<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;

class AdminController extends Controller
{
// 1. halaman admin daftar admin
    public function daftarAdmin()
    {
        $admins = Admin::orderBy('id')->get();
        return view('admin.daftar-admin', compact('admins'));
    }

// 2. halaman admin tambah admin
    public function tambahAdmin()
    {
        return view('admin.tambah-admin');
    }

// 3. menambahkan data admin ke db
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:admins,username',
            'email' => 'required|unique:admins,email',
            'password' => 'required|min:8|confirmed',
        ]);

        Admin::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('admins.index')->with('success', 'Admin berhasil ditambahkan!');
    }
// 4. halaman admin edit admin
    public function editAdmin($id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin.edit-admin', compact('admin'));
    }

// 5. update data admin di db
    public function updateAdmin(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'username' => 'required|unique:admins,username,' . $admin->id,
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $data = [
            'username' => $request->username,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $admin->update($data);

        return redirect()->route('admins.index')->with('success', 'Admin berhasil diperbarui!');
    }

// 6. hapus data admin di db
    public function destroyAdmin($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return redirect()->route('admins.index')->with('success', 'Admin berhasil dihapus!');
    }
}
