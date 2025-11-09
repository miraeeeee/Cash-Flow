@extends('layouts.auth', ['title' => 'Daftar'])

@section('content')
  <h4 class="fw-bold mb-3">Daftar</h4>
  <p class="text-muted mb-4">Buat akun baru untuk mulai mencatat keuangan Anda.</p>

  <form method="POST" action="{{ route('auth.register.submit') }}">
    @csrf
    <div class="mb-3 text-start">
      <label class="form-label">Nama</label>
      <input type="text" name="name" class="form-control" placeholder="Nama lengkap" required>
    </div>

    <div class="mb-3 text-start">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
    </div>

    <div class="mb-3 text-start">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
    </div>

    <div class="mb-3 text-start">
      <label class="form-label">Konfirmasi Password</label>
      <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
    </div>

    <div class="d-flex justify-content-between align-items-center">
      <span>Sudah punya akun? <a href="{{ route('auth.login') }}">Masuk</a></span>
      <button class="btn btn-primary px-4" type="submit">Daftar</button>
    </div>
  </form>
@endsection
