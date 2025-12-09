<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Titip Barang - Smart Locker</title>
    <link rel="icon" href="{{ asset('icon.png') }}" type="image">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
        <div class="loading-text" id="loadingText">Mempersiapkan notifikasi...</div>
        <button class="cancel-btn" id="cancelBtn">Batal</button>
    </div>

    <div class="main-container">
        <div class="card-container">
            <div class="logo-section">
                <h1>Form Titip Barang</h1>
                <span class="locker-badge">Loker No. {{ $locker->locker_number }}</span>
                <p class="subtitle">Isi data berikut untuk menitipkan barang Anda</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Terjadi kesalahan!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('titip.store') }}" id="titipForm">
                @csrf
                <input type="hidden" name="locker_id" value="{{ $locker->id }}">
                <input type="hidden" name="fcm_token" id="fcm_token" value="">

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <div class="input-icon">
                        <input type="text" name="name" class="form-control" placeholder="Masukkan nama" value="{{ old('name') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <div class="input-icon">
                        <input type="email" name="email" class="form-control" placeholder="Masukkan email untuk mengirim QR Code" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Durasi Penitipan (Jam)</label>
                    <div class="input-icon">
                        <input type="number" name="duration" class="form-control" min="1" max="48" placeholder="Durasi maksimal 48 jam" value="{{ old('duration') }}" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('titip.pilih.loker') }}" class="btn btn-kembali w-50 me-2">
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-kirim w-50 ms-2" id="submitBtn">
                        Kirim
                    </button>
                </div>
            </form>

            <p class="footer-text">© {{ date('Y') }} Smart Locker | Semua hak dilindungi</p>
        </div>
    </div>

    <script type="module"> 
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js";

        const firebaseConfig = {
            apiKey: "AIzaSyChAp6qktuAecB0_Xef212Z7qjSBcA9tE4",
            authDomain: "smart-locker-b48e8.firebaseapp.com",
            projectId: "smart-locker-b48e8",
            storageBucket: "smart-locker-b48e8.firebasestorage.app",
            messagingSenderId: "356187767479",
            appId: "1:356187767479:web:c42847d23c2c5232330332",
        };

        const app = initializeApp(firebaseConfig);
        const messaging = getMessaging(app);
        
        const loadingOverlay = document.getElementById('loadingOverlay');
        const loadingText = document.getElementById('loadingText');
        const cancelBtn = document.getElementById('cancelBtn');
        const fcmInput = document.getElementById('fcm_token');

        let isGettingToken = false;

        // Fungsi untuk show/hide loading
        function showLoading(text) {
            loadingText.textContent = text;
            loadingOverlay.classList.add('show');
        }

        function hideLoading() {
            loadingOverlay.classList.remove('show');
            cancelBtn.classList.remove('show');
        }

        // Handle cancel button
        cancelBtn.addEventListener('click', () => {
            isGettingToken = false;
            hideLoading();
            alert('Proses dibatalkan. Anda tidak akan menerima notifikasi.');
        });

        // Fungsi untuk ambil FCM Token dengan retry unlimited (sampai berhasil atau dibatalkan)
        async function getFcmToken(retryCount = 0) {
            let token = localStorage.getItem('fcmToken');

            // Jika token sudah ada di localStorage, langsung return
            if (token) {
                console.log("✅ FCM Token dari localStorage:", token);
                fcmInput.value = token;
                return token;
            }

            // Jika belum ada, request permission dan ambil token
            try {
                isGettingToken = true;
                showLoading(`Meminta izin notifikasi...`);
                
                const permission = await Notification.requestPermission();
                console.log("📱 Permission:", permission);
                
                if (permission !== 'granted') {
                    hideLoading();
                    isGettingToken = false;
                    console.warn("Notifikasi tidak diizinkan");
                    return null;
                }

                // Permission granted, langsung ambil token
                // Loop sampai token didapat atau user cancel
                while (isGettingToken) {
                    showLoading(`Mendapatkan token notifikasi...`);
                    
                    // Tampilkan tombol batal setelah 10 detik
                    if (retryCount >= 4) {
                        cancelBtn.classList.add('show');
                    }
                    
                    token = await getToken(messaging, {
                        vapidKey: "BFD54e0miGttHvYvoBYNMqQib784E6naFwm7Euz90b5i_S1KC6zfZT88uvpZhrj-wEmo5F-Xa1lQcjsifbNkV7s"
                    });

                    // Jika token berhasil didapat, keluar dari loop
                    if (token) {
                        localStorage.setItem('fcmToken', token);
                        fcmInput.value = token;
                        console.log("FCM Token berhasil:", token);
                        hideLoading();
                        isGettingToken = false;
                        return token;
                    }

                    // Jika masih null, tunggu 2 detik dan coba lagi
                    console.log(`Token null, retry ${retryCount + 1}...`);
                    await new Promise(resolve => setTimeout(resolve, 2000));
                    retryCount++;
                }

                // keluar dari loop karena cancel
                hideLoading();
                return null;

            } catch (err) {
                console.error("Error mendapatkan FCM Token:", err);
                
                // Retry otomatis jika error
                if (isGettingToken) {
                    await new Promise(resolve => setTimeout(resolve, 2000));
                    return getFcmToken(retryCount + 1);
                }
                
                hideLoading();
                return null;
            }
        }

        // Ambil token saat page load
        window.addEventListener('load', async () => {
            await getFcmToken();
        });

        // Handle form submit
        document.getElementById('titipForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Cek token di input atau localStorage
            let token = fcmInput.value || localStorage.getItem('fcmToken');
            
            // Jika tidak ada, coba ambil lagi
            if (!token) {
                showLoading('Mempersiapkan notifikasi...');
                token = await getFcmToken();
            }

            // Jika token berhasil didapat, submit form
            if (token) {
                fcmInput.value = token;
                showLoading('Mohon tunggu...');
                cancelBtn.classList.remove('show');
                console.log("Submit form dengan token");
                this.submit()
            } else {
                hideLoading();
                alert('Gagal mendapatkan token notifikasi. Silakan coba lagi atau refresh halaman.');
            }
        });
    </script>
</body>
</html>