@extends('layouts.admin')

@section('title', 'Admin - Tambah Loker')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Tambah Loker</h2>
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
    <div class="card-header bg-primary text-white">Form Tambah Loker</div>
    <div class="card-body ">
      <form method="POST" action="{{ route('lockers.store') }}">
        @csrf
        <div class="row mb-3">
          <div class="col-6">
            <div class="input-group mb-3">
              <span class="input-group-text" id="inputGroup-sizing-default">Nomor Loker</span>
              <input type="number" class="form-control" id="locker_number" name="locker_number" value="{{ old('locker_number') }}" required>
            </div> 
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-6">
            <div class="input-group mb-3">
              <span class="input-group-text" id="inputGroup-sizing-default">Kode Loker</span>
              <input type="text" class="form-control" id="locker_code" name="locker_code" value="{{ old('locker_code') }}" required> 
            </div> 
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('lockers.index') }}" class="btn btn-secondary">Kembali</a>
      </form>
    </div>
  </div>
@endsection
