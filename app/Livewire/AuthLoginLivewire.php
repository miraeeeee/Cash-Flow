<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts.auth')]
class AuthLoginLivewire extends Component
{
    #[Rule(['required','email'])]
    public string $email = '';

    #[Rule(['required','string','min:6'])]
    public string $password = '';

    public bool $remember = false;

    public function render()
    {
        return view('livewire.auth-login-livewire');
    }

    public function login(): void
    {
        $this->validate();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', 'Email atau password salah.');
            return;
        }

        session()->regenerate();
        redirect()->route('app.home');
    }
}
