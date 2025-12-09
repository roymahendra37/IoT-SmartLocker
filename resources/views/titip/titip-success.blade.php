<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sukses - Smart Locker</title>
  <link rel="icon" href="{{ asset('icon.png') }}" type="image">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('css/success.css') }}" rel="stylesheet">
</head>
<body>
  <div class="success-container">
    <div class="success-card">
      <div class="success-icon">✓</div>
      
      <h1>Titip Barang Berhasil!</h1>
      <p class="subtitle">Loker siap digunakan</p>

      <div class="info-box">
        <div class="info-item">
          <span class="info-label">🗄️ Nomor Loker</span>
          <span class="locker-badge">Loker No. {{ $locker->locker_number }}</span>
        </div>
        <div class="info-item">
          <span class="info-label">⏱️ Durasi</span>
          <span class="info-value">{{ $transaction->duration }} Jam</span>
        </div>
        <div class="info-item">
          <span class="info-label">📅 Berlaku Hingga</span>
          <span class="info-value">{{ \Carbon\Carbon::parse($transaction->end_time)->format('d/m/Y H:i') }}</span>
        </div>
      </div>

      <div class="qr-section">
        <img src="{{ asset($transaction->qr_image) }}" alt="QR Code" class="qr-code-img" id="qrCodeImage">
        <a href="{{ asset($transaction->qr_image) }}" download="QR-Code.png" class="btn-home">Simpan QR Code</a>
      </div>

      <div class="email-badge">
        <span class="email-icon">📧</span>
        QR Code telah dikirim ke: 
        <strong>{{ $transaction->email }}</strong>
      </div>

      <div class="tip-box">
        <p>
          💡 <strong>Tips:</strong> Buka email untuk mengakses QR Code kapan saja. 
          Loker dapat digunakan berkali-kali selama masa berlaku!
        </p>
      </div>

      <a href="{{ route('home') }}" class="btn-home">Kembali ke Beranda</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
