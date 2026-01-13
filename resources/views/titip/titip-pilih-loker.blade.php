<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pilih Loker - Smart Locker</title>
  <link rel="icon" href="{{ asset('icon.png') }}" type="image">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/pilih-loker.css') }}">
</head>
<body>
  <div class="container-custom">

    <div class="header-section">
      <h1>📦 Pilih Loker Tersedia</h1>
      <p>Pilih salah satu loker yang tersedia untuk menyimpan barang</p>
      <div class="mb-4 mt-4">
        <a href="{{ route('home') }}" class="select-btn text-decoration-none">Kembali</a>
      </div>
    </div>

    @if($lockers->count() > 0)
      <div class="locker-grid">
        @foreach($lockers as $locker)
          <div class="locker-card">
            <span class="locker-icon">🗄️</span>
            <div class="locker-number">
              No. {{ $locker->locker_number }}
            </div>
            <span class="locker-status status-available">
              ✓ Tersedia
            </span>
            
            <form action="{{ route('titip.pilih.loker.store') }}" method="POST">
              @csrf
              <input type="hidden" name="locker_id" value="{{ $locker->id }}">
              <button type="submit" class="select-btn">
                Pilih Loker
              </button>
            </form>
          </div>
        @endforeach
      </div>
    @else
      <div class="empty-state">
        <div class="empty-state-icon">❗</div>
        <h3>Tidak Ada Loker Tersedia</h3>
        <p>Mohon maaf, saat ini semua loker sedang terpakai. Silakan coba lagi nanti.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Kembali ke Beranda</a>
      </div>
    @endif
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>