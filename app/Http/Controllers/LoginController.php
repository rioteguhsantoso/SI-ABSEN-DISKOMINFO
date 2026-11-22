<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nip' => ['required', 'string', 'size:18'],
            'password' => ['required', 'string'],
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.size' => 'NIP harus terdiri dari 18 karakter.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);
        $key = 'login:'.Str::lower($credentials['nip']).'|'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()
                ->withInput($request->only('nip'))
                ->with('error', 'Terlalu banyak percobaan login. Silakan coba lagi dalam '.$seconds.' detik.');
        }
        $user = User::with('pegawai')->whereHas('pegawai', function ($query) use ($credentials) {
            $query->where('nip', $credentials['nip'])
                ->where('status', 'aktif');
        })->first();
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            RateLimiter::hit($key, 60);
            return back()
                ->withInput($request->only('nip'))
                ->with('error', 'NIP atau kata sandi tidak sesuai.');
        }
        RateLimiter::clear($key);
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        return redirect()->route('dashboard')
            ->with('success', 'Selamat datang, '.$user->pegawai->nama.'.');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->with('success', 'Anda berhasil keluar dari sistem.');
    }
}