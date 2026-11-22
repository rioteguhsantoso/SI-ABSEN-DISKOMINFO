<?php
namespace App\Http\Controllers;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
class PasswordResetController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required','email'],
        ],[
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);
        if (app()->environment('local')) {
            $user = Password::broker()->getUser($request->only('email'));
            if (!$user) {
                return back()->withInput($request->only('email'))->withErrors([
                    'email' => 'Email tidak ditemukan pada akun SI-ABSEN.',
                ]);
            }
            $token = Str::random(64);
            $request->session()->put('local_password_reset', [
                'email' => $user->email,
                'token_hash' => hash('sha256', $token),
                'expires_at' => now()->addMinutes(10)->timestamp,
            ]);
            return redirect()->route('password.reset', [
                'token' => $token,
                'email' => $user->email,
            ]);
        }
        $status = Password::sendResetLink($request->only('email'));
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status','Jika email terdaftar, tautan reset akan dikirim ke alamat tersebut.');
        }
        return back()->withInput($request->only('email'))->withErrors([
            'email' => 'Tautan reset tidak dapat dikirim. Periksa email atau konfigurasi pengiriman email.',
        ]);
    }
    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }
    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required','string'],
            'email' => ['required','email'],
            'password' => ['required','string','min:8','confirmed'],
        ],[
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);
        if (app()->environment('local')) {
            $data = $request->session()->get('local_password_reset');
            $tokenHash = hash('sha256', $request->input('token'));
            if (
                !$data ||
                !hash_equals($data['token_hash'], $tokenHash) ||
                !hash_equals($data['email'], $request->input('email')) ||
                now()->timestamp > $data['expires_at']
            ) {
                $request->session()->forget('local_password_reset');
                return back()->withInput($request->only('email'))->withErrors([
                    'email' => 'Token reset tidak valid atau sudah kedaluwarsa. Silakan mulai ulang.',
                ]);
            }
            $user = Password::broker()->getUser(['email' => $request->input('email')]);
            if (!$user) {
                $request->session()->forget('local_password_reset');
                return back()->withErrors([
                    'email' => 'Akun tidak ditemukan.',
                ]);
            }
            $user->forceFill([
                'password' => Hash::make($request->input('password')),
                'remember_token' => Str::random(60),
            ])->save();
            event(new PasswordReset($user));
            $request->session()->forget('local_password_reset');
            return redirect()->route('login')->with('success','Kata sandi berhasil diubah. Silakan login menggunakan NIP dan kata sandi baru.');
        }
        $status = Password::reset(
            $request->only('email','password','password_confirmation','token'),
            function ($user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
                event(new PasswordReset($user));
            }
        );
        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success','Kata sandi berhasil diubah. Silakan login menggunakan NIP dan kata sandi baru.');
        }
        return back()->withInput($request->only('email'))->withErrors([
            'email' => 'Tautan reset tidak valid atau sudah kedaluwarsa. Silakan minta tautan baru.',
        ]);
    }
}