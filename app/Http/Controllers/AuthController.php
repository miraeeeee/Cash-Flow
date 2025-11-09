<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // GET /auth/login
    public function login()
    {
        return view('pages.auth.login');
    }

    // GET /auth/register
    public function register()
    {
        return view('pages.auth.register');
    }

    // POST /auth/login
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
        ]);

        $remember = (bool) $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('app.home'));
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->withInput($request->only('email'));
    }

    // POST /auth/register
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required','string','max:100'],
            'email'                 => ['required','email','max:150','unique:users,email'],
            'password'              => ['required','min:8','confirmed'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('app.home');
    }

    // GET /auth/logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }
}
