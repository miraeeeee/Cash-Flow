@extends('layouts.auth')

@section('content')
<div class="max-w-md mx-auto card bg-base-100 shadow p-6">
    <h1 class="text-2xl font-semibold mb-4">Daftar</h1>
    @livewire('auth-register-livewire')
</div>
@endsection
