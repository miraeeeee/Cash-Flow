<!doctype html>
<html lang="id" class="scroll-smooth dark"> <!-- pakai class 'dark' untuk force dark mode -->
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'CatatanKeuangan Laravel' }}</title>
  <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">

  <!-- Tailwind (dev) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            primary:'#10b981',  /* emerald-500 */
            accent:'#8b5cf6',   /* violet-500 */
            info:'#38bdf8',     /* sky-400 */
            warning:'#f59e0b',  /* amber-500 */
            danger:'#ef4444',   /* red-500 */
          }
        }
      }
    }
  </script>

  <!-- Alpine.js -->
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>

  <style>
    .container-narrow { max-width: 1160px; }
  </style>
</head>
<body class="bg-slate-950 text-slate-200 min-h-screen">

  <!-- GRID LAYOUT -->
  <div class="min-h-screen md:grid md:grid-cols-[260px_1fr]">

    <!-- SIDEBAR -->
    <aside class="hidden md:flex md:flex-col bg-gradient-to-b from-slate-900 via-slate-900 to-slate-900 ring-1 ring-white/5">
      <div class="px-5 py-5 border-b border-white/10">
        <a href="{{ route('app.home') }}" class="font-semibold tracking-tight text-xl">
          <span class="text-white/90">Catatan</span><span class="font-bold text-white">Keuangan</span>
        </a>
        <p class="text-white/50 text-xs mt-1">Kelola pemasukan & pengeluaran</p>
      </div>

      <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
        <a href="{{ route('app.home') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition
                 {{ request()->routeIs('app.home')
                    ? 'bg-white/10 ring-1 ring-white/10 text-white'
                    : 'text-white/80 hover:bg-white/5' }}">
          <i class="bi bi-house-door"></i>
          <span>Home</span>
        </a>

        <a href="{{ route('app.finance') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition
                 {{ request()->routeIs('app.finance*')
                    ? 'bg-white/10 ring-1 ring-white/10 text-white'
                    : 'text-white/80 hover:bg-white/5' }}">
          <i class="bi bi-cash-coin"></i>
          <span>Keuangan</span>
        </a>

        <a href="{{ route('auth.logout') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition text-white/80 hover:bg-white/5">
          <i class="bi bi-box-arrow-right"></i>
          <span>Logout</span>
        </a>
      </nav>

      <div class="px-5 py-4 text-[11px] text-white/60 border-t border-white/10">
        © {{ date('Y') }} Waroengku • Dark UI
      </div>
    </aside>

    <!-- CONTENT -->
    <div class="md:p-6">

      <!-- MOBILE TOPBAR -->
      <header class="md:hidden sticky top-0 z-50">
        <div class="bg-slate-900/95 text-white shadow">
          <nav class="container mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('app.home') }}" class="font-semibold text-lg tracking-tight">
              <span class="opacity-90">Catatan</span><span class="font-bold">Keuangan</span>
            </a>
            <button class="p-2 rounded hover:bg-white/10" x-data @click="$refs.mmenu.classList.toggle('hidden')">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"/>
              </svg>
            </button>
          </nav>
          <div class="hidden border-t border-white/10" x-ref="mmenu">
            <div class="container mx-auto px-4 py-3 grid gap-3 text-sm">
              <a href="{{ route('app.home') }}" class="hover:underline">Home</a>
              <a href="{{ route('app.finance') }}" class="hover:underline">Keuangan</a>
              <a href="{{ route('auth.logout') }}" class="text-white/90 hover:underline">Logout</a>
            </div>
          </div>
        </div>
      </header>

      <!-- MAIN -->
      <main class="container mx-auto container-narrow px-4 py-6">
        <!-- wrapper glass -->
        <div class="rounded-2xl bg-slate-900/70 backdrop-blur shadow-sm ring-1 ring-white/10 p-4 md:p-6">
          @yield('content')
        </div>
      </main>
    </div>
  </div>

  <!-- Flash success via SweetAlert -->
  @if(session('success'))
  <script>
    Swal.fire({icon:'success',title:'Berhasil',text:@json(session('success')),timer:1600,showConfirmButton:false, background:'#0f172a', color:'#e2e8f0'})
  </script>
  @endif
  @if($errors->any())
  <script>
    Swal.fire({icon:'error',title:'Gagal',html:`{!! implode('<br>', $errors->all()) !!}`, background:'#0f172a', color:'#e2e8f0'})
  </script>
  @endif>
</body>
</html>
