<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts.auth')]
class AuthRegisterLivewire extends Component
{
    #[Rule(['required','string','max:100'])]
    public string $name = '';

    #[Rule(['required','email','max:190','unique:users,email'])]
    public string $email = '';

    #[Rule(['required','string','min:6','max:100'])]
    public string $password = '';

    #[Rule(['same:password'])]
    public string $password_confirmation = '';

    public function render()
    {
        return view('livewire.auth-register-livewire');
    }

    public function register(): void
    {
        $this->validate();

        $user = User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => Hash::make($this->password),
        ]);

        Auth::login($user);
        session()->regenerate();

        redirect()->route('app.home');
    }
}
