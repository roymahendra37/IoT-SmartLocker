@extends('layouts.admin')

@section('title', 'Admin - Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2>Dashboard</h2>
</div>

<!-- Status ESP32 -->
<div class="card mb-4 shadow-sm border-0 border-start border-4 border-info">
    <div class="card-body d-flex justify-content-between align-items-center py-3">
        <div>
            <h5 class="mb-0 text-muted small text-uppercase fw-bold">Status ESP32 (Polling Terakhir)</h5>
            <div class="h5 mb-0 fw-bold mt-1">
                <span id="esp-timestamp" class="text-dark">
                    @if($espLastPoll)
                        {{ \Carbon\Carbon::parse($espLastPoll)->translatedFormat('l, d F Y H:i:s') }}
                    @else
                        <span class="text-muted fst-italic">Belum ada data</span>
                    @endif
                </span>
                <small id="esp-relative" class="text-muted ms-2">
                    @if($espLastPoll)
                        ({{ \Carbon\Carbon::parse($espLastPoll)->diffForHumans() }})
                    @endif
                </small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Card Admin -->
    <div class="col-lg-4 col-md-6">
        <div class="card stat-card shadow-sm">
            <div class="card-body text-center p-4">
                <h4 class="mb-2">Admin</h4>
                <p class="stat-number mb-3">{{ $adminCount }}</p>
                <a href="{{ route('admins.index') }}" class="btn btn-primary detail-btn">Lihat Detail</a>
            </div>
        </div>
    </div>

    <!-- Card Loker -->
    <div class="col-lg-4 col-md-6">
        <div class="card stat-card shadow-sm">
            <div class="card-body text-center p-4">
                <h4 class="mb-2">Loker</h4>
                <p class="stat-number success mb-3">{{ $lockerCount }}</p>
                
                <div class="status-grid">
                    <div class="status-item">
                        <span class="badge bg-success text-light d-block mb-2">Tersedia</span>
                        <div class="count text-success">{{ $lockerAvailable }}</div>
                    </div>
                    <div class="status-item">
                        <span class="badge bg-danger text-dark d-block mb-2">Terpakai</span>
                        <div class="count text-danger">{{ $lockerOccupied }}</div>
                    </div>
                    <div class="status-item">
                        <span class="badge bg-warning text-dark d-block mb-2">Maintenance</span>
                        <div class="count text-warning">{{ $lockerMaintenance }}</div>
                    </div>
                </div>
                
                <a href="{{ route('lockers.index') }}" class="btn btn-primary detail-btn mt-3">Lihat Detail</a>
            </div>
        </div>
    </div>

    <!-- Card Transaksi -->
    <div class="col-lg-4 col-md-6">
        <div class="card stat-card shadow-sm">
            <div class="card-body text-center p-4">
                <h4 class="mb-2">Transaksi</h4>
                <p class="stat-number warning mb-3">{{ $transactionCount }}</p>
                
                <div class="status-grid">
                    <div class="status-item">
                        <span class="badge bg-success text-light d-block mb-2">Selesai</span>
                        <div class="count text-success">{{ $transactionCompleted }}</div>
                    </div>
                    <div class="status-item">
                        <span class="badge bg-warning text-dark d-block mb-2">Aktif</span>
                        <div class="count text-warning">{{ $transactionActive }}</div>
                    </div>
                    <div class="status-item">
                        <span class="badge bg-danger text-dark d-block mb-2">Expired</span>
                        <div class="count text-danger">{{ $transactionExpired }}</div>
                    </div>
                </div>
                
                <a href="{{ route('transactions.index') }}" class="btn btn-primary detail-btn mt-3"> Lihat Detail </a>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Penggunaan Loker -->
<div class="row mt-3">
  <div class="col-12">
    <div class="card shadow-lg border-0 rounded-4">
      <div class="card-header bg-primary text-white d-flex align-items-center">
        <h5 class="mb-0">Grafik Penggunaan Loker (7 Hari Terakhir)</h5>
      </div>
      <div class="card-body">
        <canvas id="usageChart" style="max-height: 300px;"></canvas>
      </div>
    </div>
  </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const ctx = document.getElementById('usageChart');
      
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: @json($labels),
          datasets: [{
            label: 'Jumlah Penggunaan Loker',
            data: @json($data),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.3,
            fill: true,
            pointRadius: 5,
            pointHoverRadius: 7
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          plugins: {
            legend: {
              display: true,
              position: 'top'
            },
            tooltip: {
              mode: 'index',
              intersect: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                stepSize: 1
              },
              title: {
                display: true,
                text: 'Jumlah Transaksi'
              }
            },
            x: {
              title: {
                display: true,
                text: 'Tanggal'
              }
            }
          }
        }
      });

      // Polling Status ESP32
      function updateEspStatus() {
        fetch('{{ route("dashboard.espStatus") }}')
            .then(response => response.json())
            .then(data => {
                const timestampEl = document.getElementById('esp-timestamp');
                const relativeEl = document.getElementById('esp-relative');

                if (data.status === 'success') {
                    // Update content
                    timestampEl.innerHTML = data.timestamp;
                    relativeEl.innerHTML = data.relative;

                    // Ensure styling is correct
                    timestampEl.classList.remove('text-muted', 'fst-italic');
                    timestampEl.classList.add('text-dark');
                } else if (data.status === 'empty') {
                    timestampEl.innerHTML = '<span class="text-muted fst-italic">Belum ada data</span>';
                    relativeEl.innerHTML = '';
                }
            })
            .catch(error => console.error('Error fetching ESP status:', error));
      }

      // Update setiap 5 detik
      setInterval(updateEspStatus, 5000);
    });
  </script>
@endsection