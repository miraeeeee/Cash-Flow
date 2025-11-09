<form wire:submit="login" class="space-y-4">
    <div>
        <label class="block text-sm mb-1">Email</label>
        <input type="email" class="border rounded px-3 py-2 w-full" wire:model.defer="email" />
        @error('email') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
    </div>
    <div>
        <label class="block text-sm mb-1">Password</label>
        <input type="password" class="border rounded px-3 py-2 w-full" wire:model.defer="password" />
        @error('password') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
    </div>
    <div class="flex items-center justify-between">
        <label class="inline-flex items-center gap-2 text-sm">
            <input type="checkbox" wire:model="remember" /> Ingat saya
        </label>
        <a href="{{ route('auth.register') }}" class="text-sm text-emerald-600">Daftar</a>
    </div>
    <button class="bg-emerald-600 text-white px-4 py-2 rounded w-full">Masuk</button>
</form>
