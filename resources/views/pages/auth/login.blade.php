@extends('layouts.auth', ['title' => 'Masuk'])

@section('content')
  <h4 class="fw-bold mb-3">Masuk</h4>
  <p class="text-muted mb-4">Silakan masuk untuk mengelola keuangan Anda.</p>

  @if ($errors->any())
    <div class="alert alert-danger py-2 text-start">
      <ul class="mb-0 small">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('auth.login.submit') }}">
    @csrf
    <div class="mb-3 text-start">
      <label for="email" class="form-label">Email</label>
      <input id="email" type="email" name="email" class="form-control" placeholder="nama@email.com" required>
    </div>

    <div class="mb-3 text-start">
      <label for="password" class="form-label">Password</label>
      <div class="input-group">
        <input id="password" type="password" name="password" class="form-control" placeholder="••••••••" required>
        <button class="btn btn-outline-secondary" type="button" onclick="togglePw('password', this)">
          <i class="bi bi-eye"></i>
        </button>
      </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="remember" id="remember">
        <label class="form-check-label" for="remember">Ingat saya</label>
      </div>
      <a href="{{ route('auth.register') }}">Daftar</a>
    </div>

    <button type="submit" class="btn btn-primary w-100">Masuk</button>
  </form>
@endsection

@push('scripts')
<script>
function togglePw(id, btn){
  const input = document.getElementById(id);
  const icon = btn.querySelector('i');
  if (input.type === 'password') {
    input.type = 'text'; icon.classList.replace('bi-eye','bi-eye-slash');
  } else {
    input.type = 'password'; icon.classList.replace('bi-eye-slash','bi-eye');
  }
}
</script>
@endpush
