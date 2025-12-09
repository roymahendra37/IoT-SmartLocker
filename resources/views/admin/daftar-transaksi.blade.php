@extends('layouts.admin')

@section('title', 'Admin - Daftar Transaksi')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Daftar Transaksi</h2>
  </div>

  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">Data Transaksi</div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped mb-0">
            <thead>
                <tr class="table-primary text-center">
                    <th style="width: 50px">#</th>
                    <th>Nama</th>
                    <th>Nomor Loker</th>
                    <th>Tipe</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse ($transactions as $transaction)
                    <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $transaction->name }}</td>
                    <td>{{ $transaction->locker->locker_number }}</td>
                    <td>
                        @if ($transaction->type == 'titip')
                        <span class="badge bg-primary text-dark">TITIP</span>
                        @else
                        <span class="badge bg-secondary text-dark">KIRIM</span>
                        @endif
                    </td>
                    <td>
                        @if ($transaction->status == 'active')
                        <span class="badge bg-warning text-dark">Aktif</span>
                        @elseif ($transaction->status == 'completed')
                        <span class="badge bg-success text-light">Selesai</span>
                        @else
                        <span class="badge bg-danger text-dark">Expired</span>
                        @endif
                    </td>
                    <td>
                        {{-- Tombol Detail --}}
                        <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-secondary btn-sm">
                            <i class="fa-solid fa-info"></i>
                        </a>

                        {{-- Tombol Buka Loker --}}
                        <form action="{{ route('transactions.bukaLoker', $transaction->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin membuka loker?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success" @if($transaction->status != 'expired') disabled @endif>
                            <i class="fa fa-unlock"></i>
                            </button>
                        </form>

                        {{-- Tombol Ubah Status ke Expired --}}
                        <form action="{{ route('transactions.expire', $transaction->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin mengubah status menjadi expired?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger" @if($transaction->status != 'active') disabled @endif>
                            <i class="fa-solid fa-xmark"></i>
                            </button>
                        </form>
                    </td>
                    </tr>
                @empty
                    <tr>
                    <td colspan="7" class="text-center">Belum ada transaksi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection