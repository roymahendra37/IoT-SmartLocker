<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Smart Locker</title>
  <link rel="icon" href="{{ asset('icon.png') }}" type="image">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">

  <style>
    body {
      background-color: #f1f6ff;
      font-family: 'Poppins', sans-serif;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0;
    }

    .login-card {
      width: 100%;
      max-width: 400px;
      border: none;
      border-radius: 1rem;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
      position: relative;
      top: -100px;
    }

    .logo {
      display: flex;
      justify-content: center;
      margin-bottom: 1rem;
    }

    .logo img {
      width: 80px;
      height: 80px;
      object-fit: contain;
    }
  </style>
</head>

<body>
  <div class="card login-card p-4">
    <div class="text-center mb-4">
      <div class="logo">
        <img src="{{ asset('icon.png') }}" alt="Smart Locker Logo">
      </div>
      <h4><strong>Smart Locker Admin</strong></h4>
    </div>

    @if(session('error'))
      <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
      @csrf

      <div class="mb-3">
        <label for="login" class="form-label">Email atau Username</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
          <input type="text" class="form-control" id="login" name="login" placeholder="Masukkan email atau username" required>
        </div>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
          <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
        </div>
      </div>

      <button type="submit" class="btn btn-primary w-100">Masuk</button>
    </form>
  </div>
</body>
</html>