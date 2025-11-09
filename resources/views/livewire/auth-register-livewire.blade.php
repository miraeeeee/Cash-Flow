<form wire:submit="register" class="space-y-4">
    <div class="form-control">
        <label class="label"><span class="label-text">Nama</span></label>
        <input type="text" class="input input-bordered w-full" wire:model.defer="name" autocomplete="name" />
        @error('name') <span class="text-error text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="form-control">
        <label class="label"><span class="label-text">Email</span></label>
        <input type="email" class="input input-bordered w-full" wire:model.defer="email" autocomplete="email" />
        @error('email') <span class="text-error text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="grid md:grid-cols-2 gap-3">
        <div class="form-control">
            <label class="label"><span class="label-text">Password</span></label>
            <input type="password" class="input input-bordered w-full" wire:model.defer="password" autocomplete="new-password" />
            @error('password') <span class="text-error text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="form-control">
            <label class="label"><span class="label-text">Konfirmasi Password</span></label>
            <input type="password" class="input input-bordered w-full" wire:model.defer="password_confirmation" autocomplete="new-password" />
            @error('password_confirmation') <span class="text-error text-sm">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="flex items-center justify-between">
        <a href="{{ route('auth.login') }}" class="link">Sudah punya akun? Masuk</a>
    </div>

    <button class="btn btn-primary w-full">Daftar</button>
</form>
