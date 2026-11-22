<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    private const REGISTRATION_STEPS = ['left', 'right'];
    private const ATTENDANCE_STEPS = ['left', 'right'];
    private function makeChallenge(string $purpose): array
    {
        $steps = $purpose === 'registration' ? self::REGISTRATION_STEPS : self::ATTENDANCE_STEPS;
        shuffle($steps);
        $token = Str::random(64);
        session(['face_challenge' => [
            'token' => $token,
            'purpose' => $purpose,
            'steps' => $steps,
            'created_at' => now('Asia/Makassar')->timestamp,
            'verified' => false,
        ]]);
        return ['token' => $token, 'steps' => $steps];
    }
    public function startFaceChallenge(Request $request)
    {
        $purpose = $request->validate(['purpose' => ['required', 'in:registration,attendance,face_update']])['purpose'];
        if ($purpose === 'registration') {
            if (!session('registration_pegawai_id') || !session('registration_user_id')) {
                return response()->json(['success' => false, 'message' => 'Sesi pendaftaran tidak ditemukan.'], 401);
            }
        } elseif (!Auth::check() || !Auth::user()->pegawai) {
            return response()->json(['success' => false, 'message' => 'Silakan login terlebih dahulu.'], 401);
        }
        return response()->json(['success' => true, 'challenge' => $this->makeChallenge($purpose)]);
    }
    public function verifyFaceChallenge(Request $request)
    {
        $data = $request->validate([
            'token' => ['required', 'string', 'size:64'],
            'purpose' => ['required', 'in:registration,attendance,face_update'],
            'completed_steps' => ['required', 'array', 'size:2'],
            'completed_steps.*' => ['required', 'string', 'in:left,right'],
            'descriptors' => ['required', 'array', 'size:2'],
            'descriptors.*' => ['required', 'array', 'size:128'],
            'descriptors.*.*' => ['required', 'numeric', 'between:-2,2'],
        ]);
        $challenge = session('face_challenge');
        if (!$challenge || !hash_equals($challenge['token'], $data['token'])) {
            return response()->json(['success' => false, 'message' => 'Tantangan tidak valid. Silakan mulai ulang.'], 422);
        }
        if (($challenge['purpose'] ?? null) !== $data['purpose']) {
            return response()->json(['success' => false, 'message' => 'Tujuan tantangan tidak sesuai.'], 422);
        }
        if (!empty($challenge['verified'])) {
            return response()->json(['success' => false, 'message' => 'Tantangan sudah diverifikasi. Mulai ulang jika diperlukan.'], 422);
        }
        if (now('Asia/Makassar')->timestamp - $challenge['created_at'] > 180) {
            session()->forget('face_challenge');
            return response()->json(['success' => false, 'message' => 'Tantangan kedaluwarsa. Silakan ulangi.'], 422);
        }
        if ($data['completed_steps'] !== $challenge['steps']) {
            session()->forget('face_challenge');
            return response()->json(['success' => false, 'message' => 'Urutan gerakan tidak sesuai. Silakan ulangi tantangan.'], 422);
        }
        $descriptors = array_values($data['descriptors']);
        $hasMovement = $this->descriptorDistance($descriptors[0], $descriptors[1]) >= 0.015;
        if (!$hasMovement) {
            session()->forget('face_challenge');
            return response()->json(['success' => false, 'message' => 'Perubahan wajah belum terdeteksi. Ulangi gerakan dengan jelas.'], 422);
        }
        session(['face_challenge' => array_merge($challenge, [
            'verified' => true,
            'verified_at' => now('Asia/Makassar')->timestamp,
        ])]);
        return response()->json(['success' => true, 'message' => 'Urutan tantangan diterima.']);
    }


    private function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000;
        $latDiff = deg2rad($lat2 - $lat1);
        $lonDiff = deg2rad($lon2 - $lon1);
        $a = sin($latDiff / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($lonDiff / 2) ** 2;
        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    private function descriptorDistance(array $a, array $b): float
    {
        if (count($a) !== 128 || count($b) !== 128) return INF;
        $sum = 0.0;
        foreach ($a as $index => $value) {
            $sum += ((float) $value - (float) $b[$index]) ** 2;
        }
        return sqrt($sum);
    }
    private function consumeChallenge(string $purpose): bool
    {
        $challenge = session('face_challenge');
        if (!$challenge || empty($challenge['verified']) || ($challenge['purpose'] ?? null) !== $purpose) return false;
        if (now('Asia/Makassar')->timestamp - ($challenge['verified_at'] ?? 0) > 60) {
            session()->forget('face_challenge');
            return false;
        }
        session()->forget('face_challenge');
        return true;
    }
    public function register()
    {
        $pegawaiId = session('registration_pegawai_id');
        $userId = session('registration_user_id');
        if (!$pegawaiId || !$userId) {
            return redirect()->route('register')->with('error', 'Silakan melakukan pendaftaran akun terlebih dahulu.');
        }
        $pegawai = Pegawai::find($pegawaiId);
        if (!$pegawai) {
            session()->forget(['registration_user_id', 'registration_pegawai_id']);
            return redirect()->route('register')->with('error', 'Data pegawai tidak ditemukan.');
        }
        return view('auth.register_face', compact('pegawai'));
    }
    public function storePegawai(Request $request)
    {
        $data = $request->validate(['face_vector' => ['required', 'string', 'max:10000']]);
        if (!session('registration_pegawai_id') || !session('registration_user_id')) {
            return response()->json(['success' => false, 'message' => 'Sesi pendaftaran tidak ditemukan.'], 401);
        }
        $vector = json_decode($data['face_vector'], true);
        if (!is_array($vector) || count($vector) !== 128) {
            return response()->json(['success' => false, 'message' => 'Format data wajah tidak valid.'], 422);
        }
        foreach ($vector as $value) {
            if (!is_numeric($value) || !is_finite((float) $value) || abs((float) $value) > 2) {
                return response()->json(['success' => false, 'message' => 'Data wajah mengandung nilai yang tidak valid.'], 422);
            }
        }
        $pegawai = Pegawai::find(session('registration_pegawai_id'));
        if (!$pegawai) {
            return response()->json(['success' => false, 'message' => 'Data pegawai tidak ditemukan.'], 404);
        }
        if (!$this->consumeChallenge('registration')) {
            return response()->json(['success' => false, 'message' => 'Pemeriksaan wajah belum selesai atau sudah kedaluwarsa.'], 422);
        }
        $pegawai->update(['face_vector' => json_encode(array_map('floatval', $vector))]);
        session()->forget(['registration_user_id', 'registration_pegawai_id', 'face_challenge']);
        return response()->json(['success' => true, 'message' => 'Wajah berhasil didaftarkan. Akun Anda sudah siap digunakan.']);
    }
    public function manageFace()
    {
        $user = Auth::user();
        if (!$user || !$user->pegawai) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Data pegawai tidak ditemukan.');
        }
        $pegawai = $user->pegawai;
        $wajahTerdaftar = !empty($pegawai->face_vector);
        return view('auth.manage_face', compact('pegawai', 'wajahTerdaftar'));
    }
    public function updateFace(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Data pegawai tidak ditemukan.',
            ], 401);
        }

        $data = $request->validate([
            'face_vector' => ['required', 'array', 'size:128'],
            'face_vector.*' => ['required', 'numeric', 'between:-2,2'],
        ]);

        $vector = array_map('floatval', $data['face_vector']);

        // Pastikan seluruh nilai descriptor valid.
        foreach ($vector as $value) {
            if (!is_finite($value)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data wajah mengandung nilai yang tidak valid.',
                ], 422);
            }
        }

        // Pastikan pemeriksaan liveness berhasil.
        if (!$this->consumeChallenge('face_update')) {
            return response()->json([
                'success' => false,
                'message' => 'Pemeriksaan wajah belum selesai atau sudah kedaluwarsa.',
            ], 422);
        }

        // Simpan dengan format JSON yang sama dengan data wajah pendaftaran.
        $user->pegawai->update([
            'face_vector' => json_encode($vector),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data wajah berhasil diperbarui.',
        ]);
    }
    public function scan()
    {
        if (!Auth::check()) return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        $pegawai = Auth::user()->pegawai;
        if (!$pegawai) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Data pegawai untuk akun ini tidak ditemukan.');
        }
        if (!$pegawai->face_vector) {
            return redirect()->route('register.face')->with('error', 'Wajah Anda belum terdaftar. Silakan lakukan pendaftaran wajah terlebih dahulu.');
        }
        $sekarang = now('Asia/Makassar');
        $hari = $sekarang->dayOfWeek;
        $jam = $sekarang->format('H:i:s');
        $absen = $pegawai->absensis()->whereDate('tanggal', $sekarang->toDateString())->first();
        $izinAktif = $pegawai->izins()
            ->where('status', 'disetujui')
            ->where('jenis_izin', '!=', 'izin_terlambat')
            ->whereDate('tanggal_mulai', '<=', $sekarang->toDateString())
            ->whereDate('tanggal_selesai', '>=', $sekarang->toDateString())
            ->first();
        if (in_array($hari, [Carbon::SATURDAY, Carbon::SUNDAY])) {
            $scanType = 'libur';
        } elseif ($izinAktif && !$absen) {
            $scanType = 'izin';
        } elseif ($absen && $absen->jam_pulang) {
            $scanType = 'selesai';
        } elseif ($absen && $absen->jam_masuk && $jam >= '16:00:00' && $jam <= '17:00:00') {
            $scanType = 'pulang';
        } elseif ($absen && $absen->jam_masuk) {
            $scanType = 'menunggu_pulang';
        } elseif ($jam >= '06:00:00' && $jam < '16:00:00') {
            $scanType = 'masuk';
        } elseif ($jam < '06:00:00') {
            $scanType = 'belum_masuk';
        } else {
            $scanType = 'berakhir';
        }
        return view('scan', compact('pegawai', 'scanType'));
    }



    public function storeAbsen(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi login telah berakhir. Silakan login kembali.'
            ], 401);
        }

        // Validasi data dari browser
        $data = $request->validate([
            'face_vector' => ['required', 'array', 'size:128'],
            'face_vector.*' => ['required', 'numeric', 'between:-2,2'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'accuracy' => ['required', 'numeric', 'min:0', 'max:1000'],
        ]);

        // Konfigurasi lokasi kantor
        $officeLat = config('attendance.office_lat');
        $officeLng = config('attendance.office_lng');
        $radius = (float) config('attendance.radius', 100);
        $maxAccuracy = (float) config('attendance.max_accuracy', 100);

        if (!is_numeric($officeLat) || !is_numeric($officeLng)) {
            return response()->json([
                'success' => false,
                'message' => 'Koordinat kantor belum dikonfigurasi. Hubungi administrator.'
            ], 503);
        }

        // Validasi akurasi GPS
        if ((float) $data['accuracy'] > $maxAccuracy) {
            return response()->json([
                'success' => false,
                'message' => 'Akurasi GPS terlalu rendah. Aktifkan lokasi presisi dan coba lagi.'
            ], 422);
        }

        // Menghitung jarak GPS pegawai ke kantor
        $distanceLocation = $this->calculateDistance(
            (float) $data['latitude'],
            (float) $data['longitude'],
            (float) $officeLat,
            (float) $officeLng
        );

        // Validasi radius kantor
        if ($distanceLocation > $radius) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi berada di luar radius absensi '
                    . $radius
                    . ' meter. Jarak terdeteksi: '
                    . round($distanceLocation)
                    . ' meter.',
            ], 422);
        }

        $pegawai = Auth::user()->pegawai;

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Data pegawai tidak ditemukan.'
            ], 404);
        }

        $sekarang = now('Asia/Makassar');
        $tanggal = $sekarang->toDateString();

        // Memeriksa izin aktif pegawai
        $izinAktif = $pegawai->izins()
            ->where('status', 'disetujui')
            ->where('jenis_izin', '!=', 'izin_terlambat')
            ->whereDate('tanggal_mulai', '<=', $tanggal)
            ->whereDate('tanggal_selesai', '>=', $tanggal)
            ->exists();

        if ($izinAktif) {
            return response()->json([
                'success' => false,
                'type' => 'izin',
                'message' => 'Anda sedang memiliki izin yang telah disetujui. Absensi tidak dapat dilakukan selama periode izin berlaku.'
            ], 422);
        }

        // Memeriksa wajah yang sudah terdaftar
        if (!$pegawai->face_vector) {
            return response()->json([
                'success' => false,
                'message' => 'Wajah pegawai belum terdaftar.'
            ], 422);
        }

        $storedVector = json_decode($pegawai->face_vector, true);

        if (!is_array($storedVector) || count($storedVector) !== 128) {
            return response()->json([
                'success' => false,
                'message' => 'Data wajah terdaftar tidak valid. Hubungi administrator.',
            ], 422);
        }

        // Menghitung jarak descriptor wajah
        $distance = $this->descriptorDistance(
            array_map('floatval', $data['face_vector']),
            array_map('floatval', $storedVector)
        );

        // Validasi kecocokan wajah
        if ($distance > 0.50) {
            return response()->json([
                'success' => false,
                'message' => 'Wajah tidak cocok dengan data pegawai yang terdaftar.',
            ], 422);
        }

        $sekarang = now('Asia/Makassar');
        $jam = $sekarang->format('H:i:s');
        $tanggal = $sekarang->toDateString();

        // Validasi hari kerja
        if (in_array($sekarang->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
            return response()->json([
                'success' => false,
                'message' => 'Absensi tidak tersedia pada hari Sabtu dan Minggu.'
            ], 422);
        }

        // Mengambil absensi pegawai hari ini
        $absen = $pegawai->absensis()
            ->whereDate('tanggal', $tanggal)
            ->first();

        // Validasi waktu masuk dan pulang
        if (!$absen) {

            if ($jam < '06:00:00') {
                return response()->json([
                    'success' => false,
                    'type' => 'masuk',
                    'message' => 'Absen masuk belum dibuka. Absen masuk dibuka mulai pukul 06:00 WITA.'
                ], 422);
            }

            if ($jam >= '16:00:00') {
                return response()->json([
                    'success' => false,
                    'type' => 'masuk',
                    'message' => 'Waktu absen masuk telah berakhir. Absen masuk hanya dapat dilakukan sebelum pukul 16:00 WITA.'
                ], 422);
            }
        } elseif ($absen->jam_pulang) {

            return response()->json([
                'success' => false,
                'type' => 'selesai',
                'message' => 'Anda sudah melakukan absensi masuk dan pulang hari ini.'
            ], 422);
        } elseif (!$absen->jam_masuk) {

            return response()->json([
                'success' => false,
                'type' => 'masuk',
                'message' => 'Data jam masuk belum tersedia. Hubungi administrator.'
            ], 422);
        } elseif ($jam < '16:00:00') {

            return response()->json([
                'success' => false,
                'type' => 'pulang',
                'message' => 'Belum waktunya absen pulang. Absen pulang dibuka pukul 16:00–17:00 WITA.'
            ], 422);
        } elseif ($jam > '17:00:00') {

            return response()->json([
                'success' => false,
                'type' => 'pulang',
                'message' => 'Waktu absen pulang telah berakhir. Batas waktu absen pulang adalah pukul 17:00 WITA.'
            ], 422);
        }

        // Memastikan Liveness Detection berhasil
        if (!$this->consumeChallenge('attendance')) {
            return response()->json([
                'success' => false,
                'message' => 'Pemeriksaan liveness belum selesai atau sudah kedaluwarsa. Silakan ulangi scan.'
            ], 422);
        }

        /*
    |--------------------------------------------------------------------------
    | Penyimpanan Absensi dan Detail Verifikasi
    |--------------------------------------------------------------------------
    | Kedua penyimpanan dilakukan dalam satu transaksi database.
    */

        $tipeAbsensi = !$absen ? 'masuk' : 'pulang';

        $absensi = DB::transaction(function () use (
            $pegawai,
            $absen,
            $tanggal,
            $jam,
            $tipeAbsensi,
            $distance,
            $distanceLocation,
            $officeLat,
            $officeLng,
            $data,
            $sekarang
        ) {

            // Menyimpan atau memperbarui absensi utama
            if ($tipeAbsensi === 'masuk') {

                $status = $jam > '08:00:00' ? 'terlambat' : 'hadir';

                $absensi = $pegawai->absensis()->create([
                    'tanggal' => $tanggal,
                    'jam_masuk' => $jam,
                    'status' => $status,
                ]);
            } else {

                $absen->update([
                    'jam_pulang' => $jam,
                ]);

                $absensi = $absen;
            }

            // Menyimpan detail hasil verifikasi
            $absensi->verifikasis()->create([
                'tipe_absensi' => $tipeAbsensi,

                // Data GPS pegawai
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'akurasi_gps' => $data['accuracy'],
                'jarak_meter' => $distanceLocation,

                // Snapshot koordinat kantor
                'office_latitude' => $officeLat,
                'office_longitude' => $officeLng,

                // Face Recognition
                'face_match_status' => 'matched',
                'face_match_distance' => $distance,

                // Liveness Detection
                'liveness_status' => 'passed',

                // Validasi lokasi
                'location_status' => 'within_radius',

                // Waktu verifikasi
                'verified_at' => $sekarang,
            ]);

            return $absensi;
        });

        // Respons setelah absensi masuk berhasil
        if ($tipeAbsensi === 'masuk') {
            return response()->json([
                'success' => true,
                'type' => 'masuk',
                'message' => 'Absensi masuk berhasil.',
                'data' => [
                    'tanggal' => $tanggal,
                    'jam_masuk' => $sekarang->format('H:i'),
                    'status' => $absensi->status,
                    'verification' => [
                        'latitude' => (float) $data['latitude'],
                        'longitude' => (float) $data['longitude'],
                        'accuracy' => (float) $data['accuracy'],
                        'distance_meter' => round($distanceLocation, 2),
                        'face_match_status' => 'matched',
                        'face_match_distance' => round($distance, 5),
                        'liveness_status' => 'passed',
                        'location_status' => 'within_radius',
                    ],
                ],
            ]);
        }

        // Respons setelah absensi pulang berhasil
        return response()->json([
            'success' => true,
            'type' => 'pulang',
            'message' => 'Absensi pulang berhasil.',
            'data' => [
                'tanggal' => $tanggal,
                'jam_masuk' => $absensi->jam_masuk,
                'jam_pulang' => $sekarang->format('H:i'),
                'status' => $absensi->status,
                'verification' => [
                    'latitude' => (float) $data['latitude'],
                    'longitude' => (float) $data['longitude'],
                    'accuracy' => (float) $data['accuracy'],
                    'distance_meter' => round($distanceLocation, 2),
                    'face_match_status' => 'matched',
                    'face_match_distance' => round($distance, 5),
                    'liveness_status' => 'passed',
                    'location_status' => 'within_radius',
                ],
            ],
        ]);
    }
}
