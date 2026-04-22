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
            // Ambil pesan error singkat untuk log internal
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();

            // Log detailnya ke file storage/logs/laravel.log agar kamu bisa cek nanti
            \Log::error("Google Login Failed: " . $errorMessage);

            // Kirim pesan tegas ke user
            return redirect()->route('login')->with('error', 
                "Gagal Login! Masalah terdeteksi pada: " . $this->getFriendlyError($errorMessage)
            );
        }
    }
    private function getFriendlyError($msg) 
    {
        if (str_contains($msg, 'Data truncated')) {
            return "Data tidak cocok (Anda tidak memiliki akses).";
        }
        if (str_contains($msg, 'default value')) {
            return "Data Role belum diatur/tidak sesuai di database.";
        }
        if (str_contains($msg, 'cURL error 77')) {
            return "Sertifikat SSL (cacert.pem) pada server/XAMPP belum terpasang.";
        }
        if (str_contains($msg, 'Connection could not be established')) {
            return "Koneksi ke layanan Email terputus.";
        }
        
        return "Gangguan sistem internal. Silakan hubungi Developer.";
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