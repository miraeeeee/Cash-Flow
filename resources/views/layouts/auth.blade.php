<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'CatatanKeuangan' }}</title>
  <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    :root{
      --bs-primary:#10b981; --bs-primary-rgb:16,185,129;
      --bs-link-color:#38bdf8; --bs-link-hover-color:#0ea5e9;
    }
    body {
      font-family: 'Inter', sans-serif;
      min-height: 100vh; display: grid; place-items:center;
      background: radial-gradient(90% 70% at 10% 10%, rgba(99,102,241,.18) 0, transparent 60%),
                  radial-gradient(80% 60% at 90% 20%, rgba(56,189,248,.14) 0, transparent 60%),
                  #0b1220;
      color: #e5e7eb;
    }
    .card-wrap { padding: 1px; border-radius: 18px;
      background: linear-gradient(135deg, rgba(139,92,246,.7), rgba(56,189,248,.7), rgba(16,185,129,.7));
      box-shadow: 0 20px 60px rgba(2,6,23,.6);
    }
    .auth-card {
      width: 100%; max-width: 460px; border-radius: 17px;
      background: rgba(15,23,42,.9); padding: 28px; border: 1px solid rgba(148,163,184,.2);
      box-shadow: 0 12px 36px rgba(2, 6, 23, .6);
      color: #e5e7eb;
    }
    .auth-logo { width: 60px; height: 60px; object-fit: contain; display:block; margin: 0 auto 10px; filter: drop-shadow(0 6px 14px rgba(0,0,0,.35)); }
    .auth-title { text-align:center; font-weight:700; font-size:1.25rem; margin-bottom: 6px; }
    .auth-subtitle { text-align:center; color:#94a3b8; font-size:.92rem; margin-bottom: 18px; }
    .btn-gradient {
      background-image: linear-gradient(90deg, #8b5cf6, #38bdf8, #10b981);
      background-size: 200% 100%; transition: background-position .3s ease, transform .1s ease;
      color: #fff; border: 0;
    }
    .btn-gradient:hover { background-position: 100% 0; transform: translateY(-1px); }
    .form-control, .form-select { background:#0b1220; border-color: rgba(148,163,184,.25); color:#e5e7eb; }
    .form-control::placeholder { color:#64748b; }
    .form-control:focus, .form-select:focus {
      border-color: rgba(56,189,248,.55); box-shadow: 0 0 0 .2rem rgba(56,189,248,.15);
    }
    .link { color: var(--bs-link-color); text-decoration: none; }
    .link:hover { color: var(--bs-link-hover-color); text-decoration: underline; }
  </style>
</head>
<body>
  <div class="card-wrap">
    <div class="auth-card">
      <img src="{{ asset('logo.png') }}" alt="Logo" class="auth-logo">
      @yield('content')
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
