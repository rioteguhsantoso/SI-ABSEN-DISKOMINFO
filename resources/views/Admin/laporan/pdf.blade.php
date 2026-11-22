<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan SI-ABSEN</title>

    <style>
        @page {
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #222;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #222;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header h2 {
            margin: 0 0 5px;
            font-size: 16px;
        }

        .header p {
            margin: 3px 0;
            font-size: 10px;
        }

        .info {
            margin-bottom: 12px;
            line-height: 1.6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #777;
            padding: 6px 5px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: #e8edf3;
            text-align: center;
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
        }
    </style>
</head>

<body>

    @php
    $judul = [
    'hadir' => 'LAPORAN KEHADIRAN PEGAWAI',
    'dinas' => 'LAPORAN PERJALANAN / KEPERLUAN DINAS',
    'terlambat' => 'LAPORAN KETERLAMBATAN PEGAWAI',
    'cuti' => 'LAPORAN CUTI PEGAWAI',
    'pengguna' => 'LAPORAN DATA PENGGUNA',
    ];

    $namaBulan = \Carbon\Carbon::createFromFormat('Y-m', $bulan)
    ->locale('id')
    ->translatedFormat('F Y');
    @endphp

    <div class="header">
        <h2>DINAS KOMUNIKASI DAN INFORMATIKA</h2>
        <p>KOTA BANJARMASIN</p>
        <p>SISTEM INFORMASI ABSENSI PEGAWAI (SI-ABSEN)</p>
    </div>

    <div class="info">
        <strong>{{ $judul[$jenis] ?? 'LAPORAN PEGAWAI' }}</strong><br>
        Periode: {{ $namaBulan }}<br>
        Tanggal Cetak: {{ \Carbon\Carbon::now('Asia/Makassar')->format('d-m-Y H:i') }} WITA
    </div>

    <table>
        <thead>
            @if ($jenis === 'hadir')
            <tr>
                <th>Tanggal</th>
                <th>NIP</th>
                <th>Nama Pegawai</th>
                <th>Masuk</th>
                <th>Pulang</th>
                <th>Status</th>
                <th>Face Masuk</th>
                <th>Liveness Masuk</th>
                <th>Lokasi Masuk</th>
                <th>Face Pulang</th>
                <th>Liveness Pulang</th>
                <th>Lokasi Pulang</th>
            </tr>
            @elseif (in_array($jenis, ['dinas', 'cuti']))
            <tr>
                <th>NIP</th>
                <th>Nama Pegawai</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Durasi</th>
                <th>Keterangan</th>
                <th>Status</th>
            </tr>
            @elseif ($jenis === 'terlambat')
            <tr>
                <th>NIP</th>
                <th>Nama Pegawai</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Status</th>
            </tr>
            @elseif ($jenis === 'pengguna')
            <tr>
                <th>NIP</th>
                <th>Nama Pegawai</th>
                <th>Nomor HP</th>
                <th>Status Pegawai</th>
                <th>Email</th>
                <th>Status Akun</th>
                <th>Pendaftaran Wajah</th>
            </tr>
            @endif
        </thead>

        <tbody>
            @forelse ($data as $item)

            @if ($jenis === 'hadir')
            @php
            $masuk = $item->verifikasis->firstWhere('tipe_absensi', 'masuk');
            $pulang = $item->verifikasis->firstWhere('tipe_absensi', 'pulang');
            @endphp

            <tr>
                <td>{{ $item->tanggal?->format('d-m-Y') ?? '-' }}</td>
                <td>{{ $item->pegawai->nip ?? '-' }}</td>
                <td>{{ $item->pegawai->nama ?? '-' }}</td>
                <td class="center">{{ $item->jam_masuk ?? '-' }}</td>
                <td class="center">{{ $item->jam_pulang ?? '-' }}</td>
                <td>{{ $item->status ?? '-' }}</td>
                <td>{{ $masuk->face_match_status ?? '-' }}</td>
                <td>{{ $masuk->liveness_status ?? '-' }}</td>
                <td>{{ $masuk->location_status ?? '-' }}</td>
                <td>{{ $pulang->face_match_status ?? '-' }}</td>
                <td>{{ $pulang->liveness_status ?? '-' }}</td>
                <td>{{ $pulang->location_status ?? '-' }}</td>
            </tr>

            @elseif (in_array($jenis, ['dinas', 'cuti']))
            @php
            $mulai = $item->tanggal_mulai
            ? \Carbon\Carbon::parse($item->tanggal_mulai)
            : null;

            $selesai = $item->tanggal_selesai
            ? \Carbon\Carbon::parse($item->tanggal_selesai)
            : $mulai;

            $durasi = ($mulai && $selesai)
            ? $mulai->diffInDays($selesai) + 1
            : 0;
            @endphp

            <tr>
                <td>{{ $item->pegawai->nip ?? '-' }}</td>
                <td>{{ $item->pegawai->nama ?? '-' }}</td>
                <td>{{ $mulai?->format('d-m-Y') ?? '-' }}</td>
                <td>{{ $selesai?->format('d-m-Y') ?? '-' }}</td>
                <td class="center">{{ $durasi }} hari</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
                <td>{{ ucfirst($item->status ?? '-') }}</td>
            </tr>

            @elseif ($jenis === 'terlambat')
            <tr>
                <td>{{ $item->pegawai->nip ?? '-' }}</td>
                <td>{{ $item->pegawai->nama ?? '-' }}</td>
                <td>
                    {{ $item->tanggal_mulai
                            ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y')
                            : '-' }}
                </td>
                <td>{{ $item->keterangan ?? '-' }}</td>
                <td>{{ ucfirst($item->status ?? '-') }}</td>
            </tr>

            @elseif ($jenis === 'pengguna')
            <tr>
                <td>{{ $item->nip ?? '-' }}</td>
                <td>{{ $item->nama ?? '-' }}</td>
                <td>{{ $item->nomor_hp ?? '-' }}</td>
                <td>{{ $item->status ?? '-' }}</td>
                <td>{{ $item->user->email ?? '-' }}</td>
                <td>{{ $item->user ? 'Terdaftar' : 'Belum Memiliki Akun' }}</td>
                <td>{{ $item->face_vector ? 'Terdaftar' : 'Belum Terdaftar' }}</td>
            </tr>
            @endif

            @empty
            <tr>
                <td colspan="12" class="center">
                    Tidak ada data laporan pada periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Banjarmasin, {{ \Carbon\Carbon::now('Asia/Makassar')->translatedFormat('d F Y') }}</p>
        <br><br><br>
        <strong>Admin SI-ABSEN</strong>
    </div>

</body>

</html>