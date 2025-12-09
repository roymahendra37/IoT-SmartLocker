@extends('layouts.admin')

@section('title', 'Admin - Tambah Admin')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Tambah Admin</h2>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">Form Tambah Admin</div>
    <div class="card-body">
      <form method="POST" action="{{ route('admins.store') }}">
        @csrf
        <div class="row mb-3">
          <div class="col-6">
            <div class="input-group mb-3">
              <span class="input-group-text">Username</span>
              <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required>
            </div>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-6">
            <div class="input-group mb-3">
              <span class="input-group-text">Email</span>
              <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            </div>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-6">
            <div class="input-group mb-3">
              <span class="input-group-text">Password</span>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-6">
            <div class="input-group mb-3">
              <span class="input-group-text">Konfirmasi Password</span>
              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admins.index') }}" class="btn btn-secondary">Kembali</a>
      </form>
    </div>
  </div>
@endsection
