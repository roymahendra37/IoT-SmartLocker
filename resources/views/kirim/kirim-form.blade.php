<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Kirim Barang - Smart Locker</title>
  <link rel="icon" href="{{ asset('icon.png') }}" type="image">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/form.css') }}">
</head>
<body>
  <!-- Loading Overlay -->
  <div class="loading-overlay" id="loadingOverlay">
      <div class="spinner"></div>
      <div class="loading-text" id="loadingText">Mohon tunggu...</div>
      <button class="cancel-btn" id="cancelBtn">Batal</button>
  </div>

  <div class="main-container">
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="card-container">
      <div class="logo-section">
        <h1>Form Kirim Barang</h1>
        <span class="locker-badge">Loker No. {{ $locker->locker_number }}</span>
        <p class="subtitle">Isi data berikut untuk mengirimkan barang Anda</p>
      </div>

      @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <form method="POST" action="{{ route('kirim.store') }}" id="kirimForm">
        @csrf
        <input type="hidden" name="locker_id" value="{{ $locker->id }}">

        <div class="mb-3">
          <label class="form-label">Nama Pengirim</label>
          <input type="text" name="name" class="form-control" placeholder="Masukkan nama pengirim" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Nama Penerima</label>
          <input type="text" name="receiver_name" class="form-control" placeholder="Masukkan nama penerima" value="{{ old('receiver_name') }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Email Penerima</label>
          <input type="email" name="receiver_email" class="form-control" placeholder="Masukkan email penerima untuk mengirim QR Code" value="{{ old('receiver_email') }}" required>
        </div>

        <div class="d-flex justify-content-between mt-4">
          <a href="{{ route('kirim.pilih.loker') }}" class="btn btn-kembali w-50 me-2">Kembali</a>
          <button type="submit" class="btn btn-kirim w-50 ms-2" id="submitBtn">Kirim</button>
        </div>
      </form>

      <p class="footer-text">© {{ date('Y') }} Smart Locker | Semua hak dilindungi</p>
    </div>
  </div>

  <script>
    const form = document.getElementById('kirimForm');
    const overlay = document.getElementById('loadingOverlay');
    const btn = document.getElementById('submitBtn');
    const cancelBtn = document.getElementById('cancelBtn');

    form.addEventListener('submit', function() {
        overlay.classList.add('show');
        btn.disabled = true;
    });

  </script>
</body>
</body>
</html>