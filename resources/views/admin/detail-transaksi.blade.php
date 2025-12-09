@extends('layouts.admin')

@section('title', 'Detail Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h2>Detail Transaksi</h2>
  <a href="{{ route('transactions.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
</div>

<div class="card shadow-sm">
  <div class="card-header bg-primary text-white">
    Informasi Transaksi
  </div>
  <div class="card-body">
    <table class="table table-bordered">
      <tr>
        <th style="width: 250px;">ID Transaksi</th>
        <td>{{ $transaction->id }}</td>
      </tr>
      <tr>
        <th>Tipe</th>
        <td>
          @if ($transaction->type == 'titip')
            <span class="badge bg-primary">TITIP</span>
          @else
            <span class="badge bg-danger">KIRIM</span>
          @endif
        </td>
      </tr>
      <tr>
        <th>Status</th>
        <td>
          @if ($transaction->status == 'active')
            <span class="badge bg-warning text-dark">Aktif</span>
          @elseif ($transaction->status == 'completed')
            <span class="badge bg-success">Selesai</span>
          @else
            <span class="badge bg-danger">Expired</span>
          @endif
        </td>
      </tr>
      <tr>
        <th>Nama</th>
        <td>{{ $transaction->name }}</td>
      </tr>
      <tr>
        <th>Email</th>
        <td>{{ $transaction->email ?? '-' }}</td>
      </tr>
      <tr>
        <th>Nama Penerima</th>
        <td>{{ $transaction->receiver_name ?? '-' }}</td>
      </tr>
      <tr>
        <th>Email Penerima</th>
        <td>{{ $transaction->receiver_email ?? '-' }}</td>
      </tr>
      <tr>
        <th>Nomor Loker</th>
        <td>{{ $transaction->locker->locker_number }}</td>
      </tr>
      <tr>
        <th>Aktivitas Scan</th>
        <td>{{ $transaction->qr_usage_count }}</td>
      </tr>
      <tr>
        <th>QR Code</th>
        <td>
          @if ($transaction->qr_code)
            <img src="{{ asset($transaction->qr_image) }}"" alt="QR Code" width="200">
            <p class="mt-2 text-muted">{{ $transaction->qr_code }}</p>
          @else
            <em>Tidak ada QR code</em>
          @endif
        </td>
      </tr>
      <tr>
        <th>Durasi</th>
        <td>{{ $transaction->duration }} jam</td>
      </tr>
      <tr>
        <th>Waktu Mulai</th>
        <td>{{ $transaction->start_time }}</td>
      </tr>
      <tr>
        <th>Waktu Selesai</th>
        <td>{{ $transaction->end_time }}</td>
      </tr>
    </table>
  </div>
</div>
@endsection