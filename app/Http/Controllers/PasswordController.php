<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __('Link pemulihan password telah dikirim ke email Anda. Silakan memeriksa inbox atau folder spam.'));
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }

    public function showResetPassword(Request $request)
    {
        return view('auth.reset-password', [
            'token' => $request->route('token'),
            'email' => $request->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'confirmed', RulesPassword::min(8)],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Password berhasil diperbarui. Silakan login dengan password baru Anda.');
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }

    public function showVerifyEmail()
    {
        $user = Auth::user();
        
        if ($user->hasVerifiedEmail()) {
            $defaultRoute = $user->role === 'admin' ? route('admin.dashboard') : route('operator.dashboard');
            return redirect()->intended($defaultRoute);
        }

        return view('auth.verify-email');
    }

    public function resendVerificationEmail(Request $request)
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $defaultRoute = $user->role === 'admin' ? route('admin.dashboard') : route('operator.dashboard');
            return redirect()->intended($defaultRoute);
        }

        $user->sendEmailVerificationNotification();

        return back()->with('status', 'Email verifikasi telah dikirim ulang. Silakan memeriksa inbox atau folder spam.');
    }
}