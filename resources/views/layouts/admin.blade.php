<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin Panel')</title>
  <link rel="icon" href="{{ asset('icon.png') }}" type="image">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
  <script src="https://kit.fontawesome.com/ada3b1c223.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

  <style>

  </style>
</head>

<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h4>Smart Locker</h4>
    <a href="{{ route('dashboard.index') }}" class="{{ request()->routeIs('dashboard.*') ? 'active' : '' }}"><i class="fa-solid fa-house"></i> Dashboard</a>
    <a href="{{ route('admins.index') }}" class="{{ request()->routeIs('admins.*') ? 'active' : '' }}"><i class="fa-solid fa-user-gear"></i> Admin</a>
    <a href="{{ route('lockers.index') }}" class="{{ request()->routeIs('lockers.*') ? 'active' : '' }}"><i class="fa-solid fa-rectangle-list"></i> Loker</a>
    <a href="{{ route('transactions.index') }}" class="{{ request()->routeIs('transactions.*') ? 'active' : '' }}"><i class="fa-solid fa-square-check"></i> Transaksi</a>
  </div>

  <!-- Topbar -->
  <div class="topbar">
    <div class="dropdown">
      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-user-circle"></i>
        <span>{{ Auth::guard('admin')->user()->username }}</span>
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenu">
        <li>
          <a class="dropdown-item p-0">
            <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Yakin ingin Logout dari akun ini?')">
            @csrf
            <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
          </form>
          </a>
        </li>
      </ul>
    </div>
  </div>

  <!-- Konten Dinamis -->
  <div class="content">
    @yield('content')
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>