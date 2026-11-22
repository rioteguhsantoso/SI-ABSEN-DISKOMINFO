<?php
namespace App\Http\Controllers;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
class RegisterController extends Controller
{
    public function showRegister()
    {
        return view('auth.register_account');
    }
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nip' => ['required','string','size:18'],
            'name' => ['required','string','max:255'],
            'email' => ['required','email','unique:users,email'],
            'password' => ['required','string','min:8','confirmed'],
        ],[
            'nip.required' => 'NIP wajib diisi.',
            'nip.size' => 'NIP harus terdiri dari 18 digit.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email tersebut sudah digunakan.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);
        $tanggalMulaiKerja = Carbon::now('Asia/Makassar')->toDateString();
        $pegawai = Pegawai::where('nip', $validated['nip'])->first();
        if ($pegawai) {
            if ($pegawai->user) {
                return back()->withInput()->with('error', 'NIP tersebut sudah memiliki akun. Silakan login.');
            }
            $pegawai->update([
                'nama' => $validated['name'],
                'status' => 'aktif',
                'tanggal_mulai_kerja' => $pegawai->tanggal_mulai_kerja ?? $tanggalMulaiKerja,
            ]);
        } else {
            $pegawai = Pegawai::create([
                'nip' => $validated['nip'],
                'nama' => $validated['name'],
                'status' => 'aktif',
                'tanggal_mulai_kerja' => $tanggalMulaiKerja,
                'face_vector' => null,
            ]);
        }
        $user = User::create([
            'pegawai_id' => $pegawai->id,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        session([
            'registration_user_id' => $user->id,
            'registration_pegawai_id' => $pegawai->id,
        ]);
        return redirect()->route('register.face')->with('success', 'Data akun berhasil dibuat. Silakan daftarkan wajah Anda.');
    }
}