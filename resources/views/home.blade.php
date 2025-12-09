<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart Locker</title>
  <link rel="icon" href="{{ asset('icon.png') }}" type="image">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/home.css') }}">
<body>
  <div class="main-container">
    <div class="card-container">
      <h1>Smart Locker</h1>
      <p class="subtitle">Pilih layanan!</p>
      
      <div class="d-grid gap-3">
        <a href="{{ route('titip.pilih.loker') }}" class="btn feature-btn btn-titip">
          <div class="btn-content">
            <span class="btn-icon">📦</span>
            <div class="btn-text">
              <div class="btn-title">TITIP BARANG</div>
              <p class="btn-desc">Simpan barang Anda dengan aman di loker</p>
            </div>
          </div>
        </a>
        
        <a href="{{ route('kirim.pilih.loker') }}" class="btn feature-btn btn-kirim">
          <div class="btn-content">
            <span class="btn-icon">📫</span>
            <div class="btn-text">
              <div class="btn-title">KIRIM BARANG</div>
              <p class="btn-desc">Kirim barang ke orang lain via loker</p>
            </div>
          </div>
        </a>
      </div>
      
      <p class="footer-text">
        💡 Scan QR code di email untuk membuka loker
      </p>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>