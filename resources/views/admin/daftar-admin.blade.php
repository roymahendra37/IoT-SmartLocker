@extends('layouts.admin')

@section('title', 'Admin - Daftar Admin')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Daftar Admin</h2>
    {{-- Tombol tambah hanya muncul jika superadmin login --}}
    @if (Auth::guard('admin')->user()->username === 'superadmin' && Auth::guard('admin')->user()->email === 'smartlocker.qr@gmail.com')
      <a href="{{ route('admins.add') }}" class="btn btn-primary">Tambah Admin</a>
    @endif
  </div>

  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">Data Admin</div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped mb-0">
          <thead>
            <tr class="table-primary text-center">
              <th style="width: 50px">#</th>
              <th>Username</th>
              <th>Email</th>
              <th>Dibuat Pada</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody class="text-center">
            @forelse ($admins as $admin)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $admin->username }}</td>
                <td>{{ $admin->email }}</td>
                <td>{{ $admin->created_at->format('d M Y H:i') }}</td>
                <td>
                  {{-- Tentukan siapa yang login --}}
                  @php
                    $currentAdmin = Auth::guard('admin')->user();
                    $isSuperAdmin = $currentAdmin->username === 'superadmin' && $currentAdmin->email === 'smartlocker.qr@gmail.com';
                  @endphp

                  {{-- Tombol Edit --}}
                  @if ($isSuperAdmin || $currentAdmin->id === $admin->id)
                    <a href="{{ route('admins.edit', $admin->id) }}" class="btn btn-sm btn-primary me-2">
                      <i class="fa fa-pencil"></i>
                    </a>
                  @endif

                  {{-- Tombol Hapus --}}
                  @if ($isSuperAdmin && !($admin->username === 'superadmin' && $admin->email === 'smartlocker.qr@gmail.com'))
                    <form action="{{ route('admins.destroy', $admin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus admin ini?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fa fa-trash"></i>
                      </button>
                    </form>
                  @elseif ($admin->username === 'superadmin' && $admin->email === 'smartlocker.qr@gmail.com')
                    <button class="btn btn-sm btn-secondary" disabled>
                      <i class="fa fa-lock"></i>
                    </button>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center">Belum ada admin</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection