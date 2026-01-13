<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart Locker</title>
  <link rel="icon" href="{{ asset('icon.png') }}" type="image">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>

<div class="main-container">
  <div class="card-container text-center">

    <!-- LOGO / HERO -->
    <div class="logo-icon">
      <img src="{{ asset('icon.png') }}" alt="Smart Locker Logo">
    </div>
    <h1>Smart Locker</h1>
    <p class="subtitle">
      Solusi penyimpanan & pengiriman barang berbasis QR Code
    </p>

    <!-- BUTTON MENU -->
    <div class="d-grid gap-3 mt-4">

      <a href="{{ route('titip.pilih.loker') }}" class="feature-btn btn-titip">
        <div class="btn-content">
          <span class="btn-icon">📦</span>
          <div class="btn-text">
            <div class="btn-title">Titip Barang</div>
            <p class="btn-desc">Simpan barang Anda dengan aman</p>
          </div>
          <span class="btn-arrow">➜</span>
        </div>
      </a>

      <a href="{{ route('kirim.pilih.loker') }}" class="feature-btn btn-kirim">
        <div class="btn-content">
          <span class="btn-icon">📫</span>
          <div class="btn-text">
            <div class="btn-title">Kirim Barang</div>
            <p class="btn-desc">Kirim barang ke pengguna lain</p>
          </div>
          <span class="btn-arrow">➜</span>
        </div>
      </a>

    </div>

    <!-- FOOTER -->
    <div class="footer-text mt-4">
      💡 Gunakan QR Code dari email untuk membuka loker
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
