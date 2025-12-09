@extends('layouts.admin')

@section('title', 'Admin - Daftar Loker')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Daftar Loker</h2>
    <a href="{{ route('lockers.add') }}" class="btn btn-primary">Tambah Loker</a>
  </div>

  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @elseif (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">Data Loker</div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped mb-0">
          <thead>
            <tr class="table-primary text-center">
              <th style="width: 50px">#</th>
              <th>Nomor Loker</th>
              <th>Kode Loker</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody class="text-center">
            @forelse ($lockers as $locker)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $locker->locker_number }}</td>
                <td>{{ $locker->locker_code }}</td>
                <td>
                  @if ($locker->status == 'available')
                    <span class="badge bg-success text-light">Tersedia</span>
                  @elseif ($locker->status == 'occupied')
                    <span class="badge bg-danger text-dark">Terpakai</span>
                  @elseif ($locker->status == 'maintenance')
                    <span class="badge bg-warning text-dark">Maintenance</span>
                  @endif
                </td>
                <td>
                  <a href="{{ route('lockers.edit', $locker->id) }}" class="btn btn-sm btn-primary me-2">
                    <i class="fa fa-pencil"></i>
                  </a>

                  {{-- Tombol ubah status maintenance / available --}}
                  <form action="{{ route('lockers.toggleStatus', $locker->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    @if($locker->status == 'available')
                      <button type="submit" class="btn btn-sm btn-warning me-2"
                        onclick="return confirm('Ubah status loker ini menjadi Maintenance?')">
                        <i class="fa fa-wrench"></i>
                      </button>
                    @elseif($locker->status == 'maintenance')
                      <button type="submit" class="btn btn-sm btn-success me-2"
                        onclick="return confirm('Ubah status loker ini menjadi Tersedia?')">
                        <i class="fa fa-check"></i>
                      </button>
                    @else
                      <button type="button" class="btn btn-sm btn-secondary me-2" disabled>
                        <i class="fa fa-ban"></i>
                      </button>
                    @endif
                  </form>

                  {{-- Tombol hapus --}}
                  <form action="{{ route('lockers.destroy', $locker->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus loker ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" @if($locker->status == 'occupied') disabled @endif>
                      <i class="fa fa-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center">Belum ada loker</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection