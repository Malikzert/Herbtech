<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect(Auth::user()->role === 'admin' ? route('admin.dashboard') : route('operator.dashboard'));
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $defaultRoute = Auth::user()->role === 'admin' ? route('admin.dashboard') : route('operator.dashboard');
            return redirect()->intended($defaultRoute);
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            config(['services.google.guzzle.verify' => false]);
            $socialUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('email', $socialUser->email)->first();

            if ($user) {
                $user->update([
                    'google_id' => $socialUser->id,
                    'avatar' => $socialUser->avatar,
                ]);
            } else {
                $user = User::create([
                    'name' => $socialUser->name,
                    'email' => $socialUser->email,
                    'google_id' => $socialUser->id,
                    'avatar' => $socialUser->avatar,
                    'password' => Hash::make(uniqid()),
                    'role' => 'operator',
                    'email_verified_at' => now(),
                ]);
            }

            Auth::login($user);
            request()->session()->regenerate();

            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->role === 'operator') {
                return redirect()->intended(route('operator.dashboard'));
            }
            return redirect()->intended('/dashboard');

        } catch (\Exception $e) {
            dd([
                'Pesan Error' => $e->getMessage(),
                'File' => $e->getFile(),
                'Baris' => $e->getLine(),
                'Trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function determineRole(string $email): string
    {
        $adminDomains = ['admin@', 'administrator@', 'herbitech.admin@'];
        foreach ($adminDomains as $domain) {
            if (str_starts_with(strtolower($email), $domain)) {
                return 'admin';
            }
        }
        return 'operator';
    }
}