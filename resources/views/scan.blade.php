@extends('layouts.app')
@section('title', 'Scan Absensi - SI-ABSEN DISKOMINFO')
@section('content')
@php
$sekarang = now('Asia/Makassar');
$jamSekarang = $sekarang->format('H:i:s');
$hariSekarang = $sekarang->dayOfWeek;
$absensiHariIni = $pegawai->absensis()->whereDate('tanggal', $sekarang->toDateString())->first();
$izinAktif = $pegawai->izins()
->where('status', 'disetujui')
->where('jenis_izin', '!=', 'izin_terlambat')
->whereDate('tanggal_mulai', '<=', $sekarang->toDateString())
    ->whereDate('tanggal_selesai', '>=', $sekarang->toDateString())
    ->first();
    $isHariLibur = in_array($hariSekarang, [\Carbon\Carbon::SATURDAY, \Carbon\Carbon::SUNDAY]);
    if ($isHariLibur) {
    $modeScan = 'libur';
    $judulScan = 'Absensi Tidak Tersedia';
    $subJudulScan = 'Hari Sabtu dan Minggu merupakan hari libur. Silakan kembali pada hari kerja.';
    } elseif ($izinAktif && !$absensiHariIni) {
    $modeScan = 'izin';
    $judulScan = 'Anda Sedang Dalam Masa Izin';
    $subJudulScan = 'Absensi dinonaktifkan selama periode izin yang telah disetujui.';
    } elseif ($absensiHariIni && $absensiHariIni->jam_pulang) {
    $modeScan = 'selesai';
    $judulScan = 'Absensi Hari Ini Selesai';
    $subJudulScan = 'Anda sudah melakukan absen masuk dan pulang.';
    } elseif ($jamSekarang >= '16:00:00' && $jamSekarang <= '17:00:00' && $absensiHariIni && $absensiHariIni->jam_masuk) {
        $modeScan = 'pulang';
        $judulScan = 'Scan Absen Pulang';
        $subJudulScan = 'Waktu absen pulang 16:00 - 17:00 WITA';
        } elseif ($absensiHariIni && $absensiHariIni->jam_masuk) {
        $modeScan = 'menunggu_pulang';
        $judulScan = 'Absen Masuk Sudah Tercatat';
        $subJudulScan = 'Absen pulang dibuka pukul 16:00 WITA';
        } elseif ($jamSekarang >= '06:00:00' && $jamSekarang < '16:00:00' ) {
            $modeScan='masuk' ;
            $judulScan='Scan Absen Masuk' ;
            $subJudulScan='Waktu absen masuk 06:00 - 16:00 WITA' ;
            } elseif ($jamSekarang < '06:00:00' ) {
            $modeScan='belum_masuk' ;
            $judulScan='Absen Belum Dibuka' ;
            $subJudulScan='Absen masuk dibuka pukul 06:00 WITA' ;
            } else {
            $modeScan='berakhir' ;
            $judulScan='Waktu Absensi Berakhir' ;
            $subJudulScan='Silakan kembali pada jadwal absensi berikutnya.' ;
            }
            @endphp

            <style>

            /* ============================================
            SI-ABSEN DISKOMINFO
            COMPACT • CLEAN • RESPONSIVE DESIGN
            ============================================ */

            .scan-page {
            min-height: calc(100vh - 80px);
            padding: 20px 16px 30px;
            background:
            radial-gradient(circle at 8% 0%, #e4efff 0, transparent 30%),
            #f4f7fc;
            color: #172b4d;
            }

            .kiosk-page {
            width: 100%;
            max-width: 1240px;
            margin: auto;
            container-type: inline-size;
            container-name: scan-layout;
            }

            /* ============================================
            HEADER
            ============================================ */

            .kiosk-header {
            position: relative;
            overflow: hidden;
            padding: 15px 20px;
            border-radius: 17px;
            background: linear-gradient(
            120deg,
            #071a38 0%,
            #103d78 58%,
            #1767b5 100%
            );
            color: #fff;
            box-shadow: 0 12px 30px rgba(12, 48, 96, .15);
            }

            .kiosk-header:after {
            content: "";
            position: absolute;
            width: 280px;
            height: 280px;
            right: -70px;
            top: -170px;
            border: 1px solid rgba(255, 255, 255, .14);
            border-radius: 50%;
            box-shadow:
            0 0 0 28px rgba(255, 255, 255, .035),
            0 0 0 58px rgba(255, 255, 255, .025);
            pointer-events: none;
            }

            .kiosk-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 1;
            }

            .kiosk-brand img {
            width: 29px;
            height: 29px;
            object-fit: contain;
            }

            .kiosk-brand .h-10 {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            }

            .kiosk-brand p:first-child {
            font-size: 13px;
            font-weight: 800;
            }

            .kiosk-brand p:last-child {
            margin-top: 3px;
            font-size: 9px;
            font-weight: 700;
            }

            .kiosk-header-right {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 10px;
            }

            .kiosk-secure {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 7px 10px;
            border: 1px solid rgba(255, 255, 255, .17);
            border-radius: 999px;
            background: rgba(255, 255, 255, .09);
            font-size: 9px;
            font-weight: 700;
            color: #dcecff;
            }

            .kiosk-back {
            display: grid;
            place-items: center;
            width: 34px;
            height: 34px;
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 10px;
            color: #fff;
            background: rgba(255, 255, 255, .1);
            transition: background .2s, transform .2s;
            }

            .kiosk-back:hover {
            background: rgba(255, 255, 255, .2);
            transform: translateX(-2px);
            }

            /* ============================================
            INTRO & DIGITAL CLOCK
            ============================================ */

            .kiosk-intro {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 16px;
            padding: 20px 3px 16px;
            }

            .kiosk-eyebrow {
            color: #3976bd;
            font-size: 9px;
            font-weight: 900;
            letter-spacing: .15em;
            text-transform: uppercase;
            }

            .kiosk-intro h1 {
            margin: 4px 0;
            color: #102b50;
            font-size: clamp(19px, 2.5vw, 24px);
            font-weight: 900;
            letter-spacing: -.035em;
            }

            .kiosk-intro p {
            margin: 0;
            color: #71839c;
            font-size: 11px;
            }

            .kiosk-clock-card {
            flex-shrink: 0;
            text-align: right;
            }

            .kiosk-clock {
            color: #123d75;
            font-size: 21px;
            font-weight: 900;
            letter-spacing: -.04em;
            font-variant-numeric: tabular-nums;
            }

            .kiosk-date {
            margin-top: 2px;
            color: #8292a8;
            font-size: 9px;
            }

            /* ============================================
            MAIN LAYOUT
            ============================================ */

            .kiosk-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.22fr) minmax(310px, .78fr);
            gap: 16px;
            align-items: start;
            }

            .kiosk-camera-column,
            .kiosk-info-column {
            min-width: 0;
            }

            .kiosk-card {
            padding: 15px;
            border: 1px solid #e5ecf5;
            border-radius: 17px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(24, 55, 95, .045);
            }

            /* ============================================
            EMPLOYEE CARD
            ============================================ */

            .employee-card {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-bottom: 11px;
            padding: 11px 13px;
            }

            .employee-avatar {
            display: grid;
            place-items: center;
            flex-shrink: 0;
            width: 37px;
            height: 37px;
            border-radius: 11px;
            background: linear-gradient(145deg, #e7f1ff, #d6e8ff);
            color: #17569b;
            }

            .employee-meta {
            min-width: 0;
            }

            .employee-meta small {
            display: block;
            color: #8a9ab0;
            font-size: 9px;
            }

            .employee-meta strong {
            display: block;
            overflow: hidden;
            margin-top: 2px;
            color: #19395f;
            font-size: 12px;
            font-weight: 900;
            text-overflow: ellipsis;
            white-space: nowrap;
            }

            .employee-meta span {
            display: block;
            margin-top: 2px;
            color: #8292a8;
            font-size: 9px;
            }

            .employee-status {
            margin-left: auto;
            padding: 5px 8px;
            border-radius: 999px;
            background: #e9f9f1;
            color: #168653;
            font-size: 8px;
            font-weight: 900;
            white-space: nowrap;
            }

            /* ============================================
            ATTENDANCE MODE
            ============================================ */

            .mode-strip {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 11px;
            padding: 10px 12px;
            border: 1px solid #dceaff;
            border-radius: 13px;
            background: linear-gradient(100deg, #f0f6ff, #f8fbff);
            }

            .mode-icon {
            display: grid;
            place-items: center;
            flex-shrink: 0;
            width: 31px;
            height: 31px;
            border-radius: 10px;
            background: #fff;
            color: #1d5b9d;
            box-shadow: 0 3px 10px rgba(26, 81, 143, .08);
            }

            .mode-strip strong {
            display: block;
            color: #17477f;
            font-size: 10px;
            font-weight: 900;
            }

            .mode-strip span {
            display: block;
            margin-top: 2px;
            color: #6c8eb8;
            font-size: 9px;
            }

            /* ============================================
            CAMERA
            ============================================ */

            .camera-box {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 10;
            min-height: 310px;
            max-height: 580px;
            overflow: hidden;
            border: 1px solid #122b4b;
            border-radius: 17px;
            background: #061326;
            box-shadow: 0 12px 28px rgba(4, 20, 42, .15);
            }

            #webcam {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scaleX(-1);
            background: #061326;
            }

            #faceCanvas {
            position: absolute;
            inset: 0;
            z-index: 5;
            width: 100%;
            height: 100%;
            pointer-events: none;
            transform: scaleX(-1);
            }

            .camera-shade {
            position: absolute;
            inset: 0;
            z-index: 6;
            pointer-events: none;
            background: linear-gradient(
            180deg,
            rgba(3, 14, 31, .35),
            transparent 25%,
            transparent 65%,
            rgba(3, 14, 31, .6)
            );
            }

            .scan-frame {
            position: absolute;
            top: 48%;
            left: 50%;
            z-index: 7;
            width: 34%;
            height: 67%;
            min-width: 150px;
            max-width: 250px;
            transform: translate(-50%, -50%);
            border: 1px dashed rgba(160, 205, 255, .65);
            border-radius: 20px;
            pointer-events: none;
            transition: opacity .2s;
            }

            .scan-frame:before,
            .scan-frame:after {
            position: absolute;
            width: 28px;
            height: 28px;
            border-color: #69b5ff;
            content: "";
            }

            .scan-frame:before {
            top: -2px;
            left: -2px;
            border-top: 3px solid #69b5ff;
            border-left: 3px solid #69b5ff;
            border-radius: 12px 0 0;
            }

            .scan-frame:after {
            right: -2px;
            bottom: -2px;
            border-right: 3px solid #69b5ff;
            border-bottom: 3px solid #69b5ff;
            border-radius: 0 0 12px;
            }

            .scan-line {
            position: absolute;
            top: 22%;
            right: 8%;
            left: 8%;
            height: 2px;
            background: linear-gradient(
            90deg,
            transparent,
            #65bdff,
            white,
            #65bdff,
            transparent
            );
            opacity: 0;
            box-shadow: 0 0 14px #55aaff;
            }

            .scanning .scan-line {
            opacity: .95;
            animation: scanLine 2s ease-in-out infinite;
            }

            @keyframes scanLine {
            0% {
            top: 18%;
            opacity: .15;
            }

            50% {
            opacity: 1;
            }

            100% {
            top: 80%;
            opacity: .15;
            }
            }

            .pulse-dot {
            animation: pulseDot 1.5s infinite;
            }

            @keyframes pulseDot {
            0%, 100% {
            opacity: 1;
            transform: scale(1);
            }

            50% {
            opacity: .45;
            transform: scale(.85);
            }
            }

            /* Camera topbar */
            .camera-topbar {
            position: absolute;
            top: 12px;
            right: 12px;
            left: 12px;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            gap: 8px;
            }

            .camera-chip {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 7px 9px;
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 999px;
            background: rgba(3, 14, 30, .48);
            backdrop-filter: blur(12px);
            color: #fff;
            font-size: 8px;
            font-weight: 800;
            }

            /* Camera status */
            .camera-status {
            position: absolute;
            right: 12px;
            bottom: 12px;
            left: 12px;
            z-index: 10;
            padding: 11px 13px;
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 13px;
            background: rgba(3, 14, 30, .72);
            backdrop-filter: blur(14px);
            text-align: center;
            color: #fff;
            }

            .camera-status strong {
            display: block;
            font-size: 11px;
            font-weight: 900;
            }

            .camera-status span {
            display: block;
            margin-top: 3px;
            color: rgba(226, 239, 255, .85);
            font-size: 9px;
            line-height: 1.5;
            }

            .camera-status.success {
            border-color: rgba(52, 211, 153, .6);
            }

            .camera-status.error {
            border-color: rgba(248, 113, 113, .65);
            }

            /* ============================================
            SUCCESS OVERLAY
            ============================================ */

            .success-overlay {
            position: absolute;
            inset: 0;
            z-index: 30;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(5, 20, 42, .93);
            backdrop-filter: blur(8px);
            }

            .success-overlay.show {
            display: flex;
            }

            .success-icon {
            animation: successPop .35s ease-out;
            }

            @keyframes successPop {
            from {
            transform: scale(.7);
            opacity: 0;
            }

            to {
            transform: scale(1);
            opacity: 1;
            }
            }

            /* ============================================
            CAMERA CAPTION
            ============================================ */

            .camera-caption {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-top: 9px;
            color: #8191a7;
            font-size: 9px;
            }

            .camera-caption b {
            color: #42658d;
            font-weight: 800;
            }

            /* ============================================
            RIGHT INFORMATION PANEL
            ============================================ */

            .info-stack {
            display: grid;
            gap: 11px;
            }

            .info-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 11px;
            }

            .info-heading h2 {
            margin: 0;
            color: #18395f;
            font-size: 12px;
            font-weight: 900;
            }

            .info-heading span {
            color: #8a9ab0;
            font-size: 8px;
            font-weight: 700;
            }

            /* Scan state */
            .scan-state {
            padding: 12px;
            border: 1px solid #dceaff;
            border-radius: 13px;
            background: linear-gradient(135deg, #f1f7ff, #fff);
            }

            .scan-state small {
            display: block;
            color: #7290b5;
            font-size: 8px;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
            }

            .scan-state strong {
            display: block;
            margin-top: 4px;
            color: #17477f;
            font-size: 12px;
            font-weight: 900;
            }

            .scan-state p {
            margin: 4px 0 0;
            color: #7890ac;
            font-size: 9px;
            line-height: 1.6;
            }

            /* Status list */
            .status-list {
            display: grid;
            gap: 9px;
            }

            .status-row {
            display: flex;
            align-items: center;
            gap: 9px;
            }

            .status-icon {
            display: grid;
            place-items: center;
            width: 30px;
            height: 30px;
            flex-shrink: 0;
            border-radius: 9px;
            background: #eff5fc;
            color: #32699f;
            }

            .status-copy {
            min-width: 0;
            flex: 1;
            }

            .status-copy strong {
            display: block;
            color: #34516f;
            font-size: 9px;
            font-weight: 900;
            }

            .status-copy span {
            display: block;
            margin-top: 2px;
            color: #91a0b3;
            font-size: 8px;
            }

            .status-pill {
            padding: 4px 7px;
            border-radius: 999px;
            background: #f1f5f9;
            color: #8291a4;
            font-size: 7px;
            font-weight: 900;
            white-space: nowrap;
            }

            .status-pill.live {
            background: #e8f8ef;
            color: #178451;
            }

            .status-pill.failed {
            background: #fff0f0;
            color: #c24141;
            }

            /* ============================================
            INSTRUCTION CARD
            ============================================ */

            .instruction-card {
            border-color: #e4ebf4;
            background: #fbfdff;
            }

            .instruction-title {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 9px;
            color: #26496f;
            font-size: 10px;
            font-weight: 900;
            }

            .instruction-card p {
            margin: 0;
            color: #7d8fa6;
            font-size: 9px;
            line-height: 1.7;
            }

            .instruction-note {
            display: flex;
            gap: 8px;
            margin-top: 11px;
            padding: 9px;
            border-radius: 11px;
            background: #eff6ff;
            color: #52769f;
            font-size: 8px;
            line-height: 1.5;
            }

            /* ============================================
            EMPTY STATE
            ============================================ */

            .empty-state {
            text-align: center;
            padding: 36px 20px;
            }

            .empty-state h2 {
            margin: 13px 0 5px;
            color: #203e62;
            font-size: 15px;
            font-weight: 900;
            }

            .empty-state p {
            max-width: 420px;
            margin: 0 auto;
            color: #8797aa;
            font-size: 10px;
            line-height: 1.7;
            }

            .empty-icon {
            display: grid;
            place-items: center;
            width: 54px;
            height: 54px;
            margin: auto;
            border-radius: 16px;
            background: #edf4fc;
            color: #3d75ad;
            }

            /* ============================================
            FOOTER
            ============================================ */

            .kiosk-footer {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-top: 14px;
            padding: 0 4px;
            color: #9aa8b9;
            font-size: 8px;
            }

            /* ============================================
            RESPONSIVE TABLET
            ============================================ */

            @container scan-layout (max-width: 900px) {
            .kiosk-layout {
            grid-template-columns: minmax(0, 1fr);
            gap: 13px;
            }

            .kiosk-camera-column,
            .kiosk-info-column {
            width: 100%;
            min-width: 0;
            }

            .kiosk-camera-column {
            grid-row: 1;
            }

            .kiosk-info-column {
            grid-row: 2;
            }

            .camera-box {
            aspect-ratio: 4 / 5;
            min-height: 340px;
            max-height: 540px;
            }
            }

            /* ============================================
            RESPONSIVE MOBILE
            ============================================ */

            @container scan-layout (max-width: 700px) {
            .scan-page {
            padding: 12px 10px 22px;
            }

            .kiosk-header {
            padding: 13px 14px;
            border-radius: 15px;
            }

            .kiosk-brand .h-10 {
            width: 34px;
            height: 34px;
            }

            .kiosk-brand p:first-child {
            font-size: 12px;
            }

            .kiosk-brand p:last-child {
            font-size: 8px;
            }

            .kiosk-secure {
            display: none;
            }

            .kiosk-intro {
            align-items: flex-start;
            padding: 17px 2px 13px;
            }

            .kiosk-intro h1 {
            font-size: 20px;
            }

            .kiosk-clock {
            font-size: 19px;
            }

            .kiosk-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 13px;
            }

            .kiosk-camera-column {
            grid-column: 1;
            grid-row: 1;
            }

            .kiosk-info-column {
            grid-column: 1;
            grid-row: 2;
            }

            .kiosk-card {
            padding: 13px;
            }

            .employee-card {
            margin-bottom: 10px;
            }

            .camera-box {
            width: 100%;
            aspect-ratio: 4 / 5;
            min-height: 300px;
            max-height: 540px;
            }

            .scan-frame {
            width: 57%;
            height: 65%;
            }

            .kiosk-footer {
            flex-direction: column;
            gap: 4px;
            }
            }

            /* ============================================
            SMALL MOBILE
            ============================================ */

            @container scan-layout (max-width: 420px) {
            .kiosk-intro {
            display: block;
            }

            .kiosk-clock-card {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 9px;
            text-align: left;
            }

            .kiosk-date {
            margin-top: 0;
            }

            .camera-box {
            min-height: 300px;
            }

            .camera-chip {
            padding: 6px 8px;
            font-size: 8px;
            }

            .employee-card {
            padding: 11px;
            }

            .employee-meta strong {
            font-size: 11px;
            }

            .employee-status {
            padding: 5px 7px;
            font-size: 8px;
            }

            .kiosk-back {
            width: 32px;
            height: 32px;
            }
            }

            </style>

            <main id="scanPage" class="scan-page">
                <div class="kiosk-page">

                    <header class="kiosk-header flex items-center justify-between gap-4">
                        <div class="kiosk-brand">
                            <div class="flex items-center gap-2.5">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/95 shadow-lg shadow-black/10">
                                    <img src="{{ asset('images/logo-header.jpg') }}"
                                        alt="Logo Diskominfo"
                                        class="h-8 w-8 object-contain">
                                </div>

                                <div>
                                    <p class="text-[15px] font-extrabold leading-none tracking-tight text-white">
                                        DISKOMINFO
                                    </p>
                                    <p class="mt-1 text-[8px] font-bold text-white/90">
                                        Kota Banjarmasin
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="kiosk-header-right">
                            <div class="kiosk-secure">
                                <span class="w-2 h-2 rounded-full bg-emerald-300"></span>
                                SISTEM ABSENSI TERINTEGRASI
                            </div>

                            <a href="{{ route('dashboard') }}" class="kiosk-back" aria-label="Kembali ke dashboard">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19l-7-7 7-7" />
                                </svg>
                            </a>
                        </div>
                    </header>

                    <section class="kiosk-intro">
                        <div>
                            <div class="kiosk-eyebrow">Smart attendance system</div>
                            <h1>{{ $judulScan }}</h1>
                            <p>Verifikasi identitas pegawai melalui pemindaian wajah.</p>
                        </div>
                        <div class="kiosk-clock-card">
                            <div id="kioskClock" class="kiosk-clock">{{ $sekarang->format('H:i:s') }}</div>
                            <div class="kiosk-date">{{ $sekarang->translatedFormat('l, d F Y') }} · WITA</div>
                        </div>
                    </section>
                    <div class="kiosk-layout">
                        <section class="kiosk-camera-column">
                            <div class="kiosk-card employee-card">
                                <div class="employee-avatar"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19a4 4 0 00-6 0m6-10a3 3 0 11-6 0 3 3 0 016 0zm6 10a7 7 0 00-14 0" />
                                    </svg></div>
                                <div class="employee-meta"><small>PEGAWAI TERDAFTAR</small><strong>{{ $pegawai->nama }}</strong><span>NIP {{ $pegawai->nip }}</span></div>
                                [ ]+$
                                <div class="employee-status">
                                    @if(in_array($modeScan, ['masuk', 'pulang']))
                                    SIAP SCAN
                                    @elseif($modeScan === 'menunggu_pulang')
                                    SUDAH ABSEN MASUK
                                    @elseif($modeScan === 'selesai')
                                    SELESAI
                                    @elseif($modeScan === 'libur')
                                    HARI LIBUR
                                    @else
                                    MENUNGGU
                                    @endif
                                </div>
                            </div>
                            @if(in_array($modeScan, ['masuk','pulang']))
                            <div class="mode-strip">
                                <div class="mode-icon">
                                    @if($modeScan === 'pulang')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v18m9-9H3" />
                                    </svg>
                                    @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    @endif
                                </div>
                                <div><strong>{{ $modeScan === 'pulang' ? 'MODE ABSEN PULANG' : 'MODE ABSEN MASUK' }}</strong><span>{{ $subJudulScan }}</span></div>
                            </div>
                            <div id="cameraBox" class="camera-box">
                                <video id="webcam" autoplay muted playsinline></video>
                                <canvas id="faceCanvas"></canvas>
                                <div class="camera-shade"></div>
                                <div class="scan-frame">
                                    <div class="scan-line"></div>
                                </div>
                                <div class="camera-topbar">
                                    <div class="camera-chip"><span id="cameraDot" class="w-2 h-2 rounded-full bg-amber-400 pulse-dot"></span><span id="cameraLabel">Mengaktifkan kamera</span></div>
                                    <div class="camera-chip">{{ $modeScan === 'pulang' ? 'ABSEN PULANG' : 'ABSEN MASUK' }}</div>
                                </div>
                                <div id="cameraStatus" class="camera-status"><strong id="statusText">Mempersiapkan kamera...</strong><span id="statusSub">Mohon tunggu sebentar</span></div>
                                <div id="resultOverlay" class="success-overlay">
                                    <div class="text-center text-white px-6">
                                        <div id="resultIcon" class="success-icon w-20 h-20 mx-auto rounded-full bg-emerald-500 flex items-center justify-center shadow-lg">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <h2 id="resultTitle" class="text-xl font-extrabold mt-5">Absensi Berhasil</h2>
                                        <p id="resultMessage" class="text-sm text-white/80 mt-2">-</p>
                                        <p id="resultSubMessage" class="text-xs text-white/60 mt-2">-</p>
                                    </div>
                                </div>
                            </div>
                            <div class="camera-caption"><span><b>AREA PEMINDAIAN WAJAH</b> · Pastikan wajah terlihat jelas</span><span>FACE ID <b>ACTIVE</b></span></div>
                            @else
                            <div class="kiosk-card empty-state">
                                @if($modeScan === 'libur')

                                <div class="empty-icon">
                                    {{-- Ikon kalender yang sudah ada --}}
                                </div>

                                <h2>Hari Libur</h2>
                                <p>Fitur scan absensi tidak tersedia pada hari Sabtu dan Minggu. Silakan kembali pada hari kerja.</p>

                                @elseif($modeScan === 'izin')
                                <div class="empty-icon">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="1.8"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h2>Anda Sedang Dalam Masa Izin</h2>
                                <p>
                                    Pengajuan izin Anda telah disetujui.
                                    Fitur scan absensi tidak tersedia selama periode izin berlaku.
                                </p>
                                <div class="mt-5 rounded-xl bg-emerald-50 px-4 py-3 text-left">
                                    <p class="text-[10px] font-bold text-emerald-700">
                                        {{ ucfirst(str_replace('_', ' ', $izinAktif->jenis_izin)) }}
                                    </p>
                                    <p class="mt-1 text-[10px] text-emerald-600">
                                        Periode:
                                        {{ \Carbon\Carbon::parse($izinAktif->tanggal_mulai)->format('d M Y') }}
                                        -
                                        {{ \Carbon\Carbon::parse($izinAktif->tanggal_selesai)->format('d M Y') }}
                                    </p>
                                    <p class="mt-2 text-[10px] text-emerald-600">
                                        {{ $izinAktif->keterangan }}
                                    </p>
                                </div>
                                @else
                                <div class="empty-icon"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg></div>
                                <h2>{{ $judulScan }}</h2>
                                <p>{{ $subJudulScan }}</p>
                                @if($modeScan === 'menunggu_pulang')
                                <div class="mt-5 rounded-xl bg-blue-50 px-4 py-3">
                                    <p class="text-[10px] font-bold text-blue-800">Absen masuk tercatat</p>
                                    <p class="mt-1 text-[10px] text-blue-600">Jam masuk: {{ \Carbon\Carbon::parse($absensiHariIni->jam_masuk)->format('H:i') }} WITA</p>
                                </div>
                                @elseif($modeScan === 'selesai')
                                <div class="mt-5 rounded-xl bg-emerald-50 px-4 py-3">
                                    <p class="text-[10px] font-bold text-emerald-700">Absensi hari ini lengkap</p>
                                    <p class="mt-1 text-[10px] text-emerald-600">Masuk {{ \Carbon\Carbon::parse($absensiHariIni->jam_masuk)->format('H:i') }} WITA · Pulang {{ \Carbon\Carbon::parse($absensiHariIni->jam_pulang)->format('H:i') }} WITA</p>
                                </div>
                                @endif
                                @endif
                            </div>
                            @endif
                        </section>
                        <aside class="kiosk-info-column info-stack">
                            <div class="kiosk-card">
                                <div class="info-heading">
                                    <h2>Status Verifikasi</h2><span>REAL-TIME MONITOR</span>
                                </div>
                                <div class="scan-state"><small>Proses saat ini</small><strong id="sideStatusTitle">Menyiapkan pemindaian</strong>
                                    <p id="sideStatusDescription">Sistem sedang menyiapkan kamera dan model pengenalan wajah.</p>
                                </div>
                                <div class="mt-4 status-list">
                                    <div class="status-row">
                                        <div class="status-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 11a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 0114 0" />
                                            </svg></div>
                                        <div class="status-copy"><strong>Face Recognition</strong><span>Pencocokan wajah pegawai</span></div><span id="faceState" class="status-pill">MENUNGGU</span>
                                    </div>
                                    <div class="status-row">
                                        <div class="status-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V7l7-4z" />
                                            </svg></div>
                                        <div class="status-copy"><strong>Liveness Detection</strong><span>Verifikasi gerakan kepala</span></div><span id="livenessState" class="status-pill">MENUNGGU</span>
                                    </div>
                                    <div class="status-row">
                                        <div class="status-icon"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s7-4.4 7-11a7 7 0 10-14 0c0 6.6 7 11 7 11z" />
                                                <circle cx="12" cy="10" r="2" />
                                            </svg></div>
                                        <div class="status-copy"><strong>Validasi Lokasi GPS</strong><span id="gpsDescription">Pemeriksaan koordinat perangkat</span></div><span id="gpsState" class="status-pill">MENUNGGU</span>
                                    </div>
                                </div>
                            </div>
                            <div class="kiosk-card instruction-card">
                                <div class="instruction-title"><span class="status-icon">✦</span> Petunjuk Pemindaian</div>
                                <p>1. Posisikan wajah di tengah kamera.<br>2. Pastikan wajah terlihat jelas dan pencahayaan cukup.<br>3. Ikuti instruksi gerakan kepala ke kiri dan kanan.<br>4. Izinkan akses lokasi perangkat saat diminta.</p>
                                <div class="instruction-note"><span>ⓘ</span><span>Gunakan perangkat di area kantor dan pastikan koneksi internet serta izin kamera dan lokasi aktif.</span></div>
                            </div>
                            <div class="kiosk-card">
                                <div class="info-heading mb-2">
                                    <h2>Keamanan Sistem</h2><span>SI-ABSEN</span>
                                </div>
                                <p class="text-[10px] leading-relaxed text-slate-400">Verifikasi dilakukan melalui pencocokan data wajah, pemeriksaan liveness, dan validasi lokasi sebelum absensi dikirim.</p>
                            </div>
                        </aside>
                    </div>
                    <footer class="kiosk-footer"><span>© {{ date('Y') }} SI-ABSEN · DISKOMINFO Kota Banjarmasin</span><span>Face Recognition · Liveness Detection · GPS Validation</span></footer>
                </div>
            </main>
            @endsection

            @push('scripts')
            <script src="{{ asset('js/face-api.min.js') }}"></script>
            <script>
                const clockElement = document.getElementById('kioskClock');
                if (clockElement) {
                    const updateClock = () => {
                        clockElement.textContent = new Intl.DateTimeFormat('id-ID', {
                            timeZone: 'Asia/Makassar',
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit',
                            hour12: false
                        }).format(new Date());
                    };
                    updateClock();
                    setInterval(updateClock, 1000);
                }

                const video = document.getElementById('webcam');
                const canvas = document.getElementById('faceCanvas');
                const cameraBox = document.getElementById('cameraBox');
                const statusText = document.getElementById('statusText');
                const statusSub = document.getElementById('statusSub');
                const cameraStatus = document.getElementById('cameraStatus');
                const sideStatusTitle = document.getElementById('sideStatusTitle');
                const sideStatusDescription = document.getElementById('sideStatusDescription');
                const faceState = document.getElementById('faceState');
                const livenessState = document.getElementById('livenessState');
                const gpsState = document.getElementById('gpsState');
                const gpsDescription = document.getElementById('gpsDescription');
                const cameraDot = document.getElementById('cameraDot');
                const cameraLabel = document.getElementById('cameraLabel');
                const resultOverlay = document.getElementById('resultOverlay');
                const resultIcon = document.getElementById('resultIcon');
                const resultTitle = document.getElementById('resultTitle');
                const resultMessage = document.getElementById('resultMessage');
                const resultSubMessage = document.getElementById('resultSubMessage');

                const storedFaceVector = @json($pegawai - > face_vector);
                const modeScan = @json($modeScan);
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

                let referenceDescriptor = null;
                let stream = null;
                let scanning = false;
                let processing = false;
                let detecting = false;
                let challengeActive = false;
                let challengeSubmitting = false;
                let challengeToken = null;
                let challengeSteps = [];
                let completedSteps = [];
                let challengeDescriptors = [];
                let challengeIndex = 0;
                let stableFrames = 0;
                let latestDescriptor = null;
                let lastChallengeFrame = 0;
                let lastDetectionTime = 0;

                const STEP_STABLE_FRAMES = 2;
                const DETECTION_INTERVAL = 90;
                const FACE_MATCH_THRESHOLD = 0.50;
                const TURN_LEFT_THRESHOLD = 0.56;
                const TURN_RIGHT_THRESHOLD = 0.44;

                function setStatus(main, sub = '', type = 'normal') {
                    if (statusText) statusText.textContent = main;
                    if (statusSub) statusSub.textContent = sub;
                    if (sideStatusTitle) sideStatusTitle.textContent = main;
                    if (sideStatusDescription) sideStatusDescription.textContent = sub || 'Sistem sedang menjalankan pemeriksaan.';

                    if (cameraStatus) {
                        cameraStatus.classList.toggle('success', type === 'success');
                        cameraStatus.classList.toggle('error', type === 'error');
                    }

                    if (faceState && /wajah terdeteksi|pemeriksaan liveness|gerakan terdeteksi|memverifikasi gerakan|pemeriksaan berhasil/i.test(main)) {
                        faceState.textContent = 'TERDETEKSI';
                        faceState.classList.add('live');
                        faceState.classList.remove('failed');
                    }

                    if (livenessState && /pemeriksaan liveness|gerakan terdeteksi|memverifikasi gerakan|pemeriksaan berhasil/i.test(main)) {
                        livenessState.textContent = /pemeriksaan berhasil/i.test(main) ? 'LOLOS' : 'AKTIF';
                        livenessState.classList.add('live');
                        livenessState.classList.remove('failed');
                    }

                    if (cameraDot) {
                        cameraDot.className = type === 'success' ?
                            'w-2 h-2 rounded-full bg-emerald-400' :
                            type === 'error' ?
                            'w-2 h-2 rounded-full bg-red-400' :
                            'w-2 h-2 rounded-full bg-blue-400 pulse-dot';
                    }
                }

                function setPill(element, value, type = 'normal') {
                    if (!element) return;
                    element.textContent = value;
                    element.classList.toggle('live', type === 'success');
                    element.classList.toggle('failed', type === 'error');
                }

                function showResult(title, message, subMessage, type = 'success') {
                    if (!resultOverlay) return;
                    resultTitle.textContent = title;
                    resultMessage.textContent = message;
                    resultSubMessage.textContent = subMessage;

                    resultIcon.className = type === 'success' ?
                        'success-icon w-20 h-20 mx-auto rounded-full bg-emerald-500 flex items-center justify-center shadow-lg' :
                        'success-icon w-20 h-20 mx-auto rounded-full bg-amber-500 flex items-center justify-center shadow-lg';

                    resultIcon.innerHTML = type === 'success' ?
                        '<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>' :
                        '<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01"/></svg>';

                    resultOverlay.classList.add('show');
                }

                async function postJson(url, data) {
                    if (!csrfToken) {
                        throw new Error('Token keamanan tidak ditemukan. Muat ulang halaman.');
                    }

                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json().catch(() => ({}));

                    if (!response.ok || result.success !== true) {
                        throw new Error(result.message || `Permintaan gagal (${response.status}).`);
                    }

                    return result;
                }

                async function loadModels() {
                    if (typeof faceapi === 'undefined') {
                        throw new Error('Library face-api.js tidak ditemukan.');
                    }

                    const modelPath = "{{ asset('models') }}";

                    await Promise.all([
                        faceapi.nets.ssdMobilenetv1.loadFromUri(modelPath),
                        faceapi.nets.faceLandmark68Net.loadFromUri(modelPath),
                        faceapi.nets.faceRecognitionNet.loadFromUri(modelPath)
                    ]);
                }

                function prepareReferenceFace() {
                    try {
                        const vector = typeof storedFaceVector === 'string' ?
                            JSON.parse(storedFaceVector) :
                            storedFaceVector;

                        if (!Array.isArray(vector) || vector.length !== 128 || !vector.every(Number.isFinite)) {
                            throw new Error('Format vektor tidak valid.');
                        }

                        referenceDescriptor = new Float32Array(vector);
                    } catch (error) {
                        throw new Error('Data wajah pegawai tidak valid. Silakan daftarkan wajah kembali.');
                    }
                }

                async function startCamera() {
                    if (!navigator.mediaDevices?.getUserMedia) {
                        throw new Error('Browser tidak mendukung kamera. Gunakan localhost atau HTTPS.');
                    }

                    stream = await navigator.mediaDevices.getUserMedia({
                        audio: false,
                        video: {
                            facingMode: 'user',
                            width: {
                                ideal: 640
                            },
                            height: {
                                ideal: 480
                            },
                            frameRate: {
                                ideal: 30,
                                max: 30
                            }
                        }
                    });

                    video.srcObject = stream;

                    await new Promise((resolve, reject) => {
                        if (video.readyState >= 1) return resolve();
                        video.onloadedmetadata = resolve;
                        video.onerror = () => reject(new Error('Kamera gagal dimuat.'));
                    });

                    await video.play();

                    cameraLabel.textContent = 'Kamera aktif';

                    cameraDot.className = 'w-2 h-2 rounded-full bg-emerald-400';

                    canvas.width = video.videoWidth || 640;
                    canvas.height = video.videoHeight || 480;

                    cameraBox.classList.add('scanning');
                }

                function getHeadTurnRatio(landmarks) {
                    const leftEye = landmarks.getLeftEye();
                    const rightEye = landmarks.getRightEye();
                    const nose = landmarks.getNose();

                    if (!leftEye.length || !rightEye.length || nose.length < 7) {
                        return 0.5;
                    }

                    const eyeMinX = Math.min(
                        ...leftEye.map(point => point.x),
                        ...rightEye.map(point => point.x)
                    );

                    const eyeMaxX = Math.max(
                        ...leftEye.map(point => point.x),
                        ...rightEye.map(point => point.x)
                    );

                    const noseCenterX = nose
                        .slice(3, 8)
                        .reduce((total, point) => total + point.x, 0) / 5;

                    const eyeWidth = eyeMaxX - eyeMinX;

                    if (eyeWidth <= 0) return 0.5;

                    return 1 - ((noseCenterX - eyeMinX) / eyeWidth);
                }

                function getStepInstruction(step) {
                    if (step === 'left') {
                        return 'Tolehkan kepala ke kiri Anda.';
                    }

                    if (step === 'right') {
                        return 'Tolehkan kepala ke kanan Anda.';
                    }

                    return 'Gerakan tidak dikenali.';
                }

                function resetChallenge() {
                    challengeActive = false;
                    challengeSubmitting = false;
                    challengeToken = null;
                    challengeSteps = [];
                    completedSteps = [];
                    challengeDescriptors = [];
                    challengeIndex = 0;
                    stableFrames = 0;
                    lastChallengeFrame = 0;
                }

                async function startChallenge() {
                    const result = await postJson(
                        "{{ route('face.registration.challenge.start') }}", {
                            purpose: 'attendance'
                        }
                    );

                    const challenge = result.challenge;

                    if (!challenge?.token || !Array.isArray(challenge.steps) || challenge.steps.length !== 2) {
                        throw new Error('Server harus mengirim dua gerakan: kiri dan kanan.');
                    }

                    if (!['left', 'right'].every(step => challenge.steps.includes(step))) {
                        throw new Error('Server harus mengirim gerakan kiri dan kanan.');
                    }

                    challengeToken = challenge.token;
                    challengeSteps = challenge.steps;
                    completedSteps = [];
                    challengeDescriptors = [];
                    challengeIndex = 0;
                    stableFrames = 0;
                    challengeActive = true;

                    setPill(livenessState, 'AKTIF');
                    setStatus('Pemeriksaan liveness', getStepInstruction(challengeSteps[0]));
                }

                function processChallenge(detection) {
                    if (!challengeActive || challengeIndex >= challengeSteps.length || challengeSubmitting) {
                        return;
                    }

                    const nowFrame = performance.now();

                    if (nowFrame - lastChallengeFrame < 80) return;

                    lastChallengeFrame = nowFrame;

                    const step = challengeSteps[challengeIndex];
                    const ratio = getHeadTurnRatio(detection.landmarks);

                    const passed = step === 'left' ?
                        ratio > TURN_LEFT_THRESHOLD :
                        ratio < TURN_RIGHT_THRESHOLD;

                    stableFrames = passed ? stableFrames + 1 : 0;

                    if (passed) {
                        setStatus(
                            'Pertahankan posisi',
                            'Bagus! Gerakan terdeteksi. Tahan kepala sebentar lagi.'
                        );
                    } else {
                        const instruction = getStepInstruction(step);

                        setStatus(
                            'Arahkan kepala sedikit lagi',
                            `${instruction} Gerakan belum cukup jauh.`
                        );
                    }

                    if (stableFrames < STEP_STABLE_FRAMES) return;

                    completedSteps.push(step);
                    challengeDescriptors.push(Array.from(detection.descriptor));
                    challengeIndex++;
                    stableFrames = 0;

                    if (challengeIndex < challengeSteps.length) {
                        setStatus(
                            'Gerakan berhasil',
                            `Bagus! ${getStepInstruction(challengeSteps[challengeIndex])}`,
                            'success'
                        );
                        return;
                    }

                    challengeActive = false;
                    challengeSubmitting = true;

                    verifyChallengeAndSubmit();
                }

                function drawFace(detection) {
                    const context = canvas.getContext('2d');

                    context.clearRect(0, 0, canvas.width, canvas.height);

                    if (!detection) return;

                    const resized = faceapi.resizeResults(detection, {
                        width: canvas.width,
                        height: canvas.height
                    });

                    const box = resized.detection.box;

                    context.save();
                    context.strokeStyle = '#45C7FF';
                    context.lineWidth = Math.max(2, canvas.width / 320);
                    context.shadowColor = '#45C7FF';
                    context.shadowBlur = 12;
                    context.strokeRect(box.x, box.y, box.width, box.height);
                    context.restore();

                    faceapi.draw.drawFaceLandmarks(canvas, [resized]);
                }

                function clearFaceDrawing() {
                    const context = canvas?.getContext('2d');
                    if (context) context.clearRect(0, 0, canvas.width, canvas.height);
                }

                async function scanFace() {
                    if (!scanning || processing || detecting) return;

                    if (video.readyState < 2) {
                        requestAnimationFrame(scanFace);
                        return;
                    }

                    detecting = true;
                    lastDetectionTime = performance.now();

                    try {
                        const detection = await faceapi.detectSingleFace(
                                video,
                                new faceapi.SsdMobilenetv1Options({
                                    inputSize: 320,
                                    scoreThreshold: 0.5
                                })
                            )
                            .withFaceLandmarks()
                            .withFaceDescriptor();

                        clearFaceDrawing();

                        if (!detection) {
                            latestDescriptor = null;
                            stableFrames = 0;

                            if (challengeActive) {
                                setStatus(
                                    'Wajah tidak terdeteksi',
                                    'Posisikan seluruh wajah di depan kamera.'
                                );
                            } else {
                                setStatus(
                                    'Mencari wajah...',
                                    'Posisikan wajah di tengah kamera dan pastikan pencahayaan cukup.'
                                );
                            }

                            return;
                        }

                        latestDescriptor = Array.from(detection.descriptor);

                        drawFace(detection);

                        const distance = faceapi.euclideanDistance(
                            referenceDescriptor,
                            detection.descriptor
                        );

                        if (distance > FACE_MATCH_THRESHOLD) {
                            stableFrames = 0;
                            setPill(faceState, 'TIDAK COCOK', 'error');

                            setStatus(
                                'Wajah belum cocok',
                                'Pastikan menggunakan wajah pegawai yang terdaftar.',
                                'error'
                            );

                            return;
                        }

                        setPill(faceState, 'TERDETEKSI', 'success');

                        if (challengeActive) {
                            processChallenge(detection);
                            return;
                        }

                        if (challengeSubmitting || processing) {
                            setStatus(
                                'Wajah terdeteksi',
                                'Sedang memverifikasi dan menyimpan absensi...'
                            );
                            return;
                        }

                        if (!challengeToken) {
                            setStatus(
                                'Wajah terdeteksi',
                                'Menyiapkan pemeriksaan dua gerakan kepala...'
                            );
                            return;
                        }

                        setStatus(
                            'Wajah terdeteksi',
                            'Ikuti petunjuk gerakan kepala di layar kamera.'
                        );

                    } catch (error) {
                        console.error('Scan error:', error);

                        setStatus(
                            'Terjadi kesalahan',
                            error.message || 'Pemeriksaan wajah mengalami kendala.',
                            'error'
                        );

                    } finally {
                        detecting = false;

                        if (scanning && !processing) {
                            const elapsed = performance.now() - lastDetectionTime;
                            const delay = Math.max(30, DETECTION_INTERVAL - elapsed);

                            setTimeout(() => {
                                requestAnimationFrame(scanFace);
                            }, delay);
                        }
                    }
                }
            </script>

            <script>
                async function verifyChallengeAndSubmit() {
                    try {
                        if (completedSteps.length !== 2 || challengeDescriptors.length !== 2) {
                            throw new Error('Dua gerakan belum selesai. Silakan ulangi.');
                        }

                        setPill(livenessState, 'MEMVERIFIKASI');
                        setStatus(
                            'Memverifikasi gerakan',
                            'Pemeriksaan liveness sedang dikirim ke server.'
                        );

                        await postJson(
                            "{{ route('face.registration.challenge.verify') }}", {
                                token: challengeToken,
                                purpose: 'attendance',
                                completed_steps: completedSteps,
                                descriptors: challengeDescriptors
                            }
                        );

                        if (!latestDescriptor) {
                            throw new Error('Wajah tidak terdeteksi. Silakan ulangi scan.');
                        }

                        const distance = faceapi.euclideanDistance(
                            referenceDescriptor,
                            new Float32Array(latestDescriptor)
                        );

                        if (distance > FACE_MATCH_THRESHOLD) {
                            throw new Error('Wajah tidak cocok dengan data pegawai terdaftar.');
                        }

                        setPill(livenessState, 'LOLOS', 'success');

                        await submitAttendance(latestDescriptor);

                    } catch (error) {
                        console.error('Verifikasi scan gagal:', error);

                        resetChallenge();
                        processing = false;
                        scanning = true;

                        setPill(livenessState, 'GAGAL', 'error');

                        setStatus(
                            'Pemeriksaan gagal',
                            error.message || 'Silakan ulangi scan.',
                            'error'
                        );

                        setTimeout(() => {
                            if (scanning && !processing) {
                                beginAttendanceChallenge();
                            }
                        }, 1200);
                    }
                }

                function getCurrentLocation() {
                    return new Promise((resolve, reject) => {
                        if (!navigator.geolocation) {
                            reject(new Error('Browser tidak mendukung layanan lokasi.'));
                            return;
                        }

                        navigator.geolocation.getCurrentPosition(
                            position => {
                                resolve({
                                    latitude: position.coords.latitude,
                                    longitude: position.coords.longitude,
                                    accuracy: position.coords.accuracy
                                });
                            },
                            error => {
                                let message = 'Gagal mengambil lokasi perangkat.';

                                if (error.code === error.PERMISSION_DENIED) {
                                    message = 'Izin lokasi ditolak. Izinkan akses lokasi pada browser.';
                                } else if (error.code === error.POSITION_UNAVAILABLE) {
                                    message = 'Lokasi perangkat tidak tersedia. Periksa layanan lokasi.';
                                } else if (error.code === error.TIMEOUT) {
                                    message = 'Pengambilan lokasi terlalu lama. Silakan coba lagi.';
                                }

                                reject(new Error(message));
                            }, {
                                enableHighAccuracy: true,
                                timeout: 20000,
                                maximumAge: 5000
                            }
                        );
                    });
                }

                async function submitAttendance(faceVector) {
                    processing = true;
                    scanning = false;

                    let locationObtained = false;

                    try {
                        setPill(gpsState, 'MEMERIKSA');
                        if (gpsDescription) {
                            gpsDescription.textContent = 'Sedang mengambil koordinat perangkat.';
                        }

                        setStatus(
                            'Memeriksa lokasi',
                            'Mengambil koordinat GPS perangkat...'
                        );

                        const location = await getCurrentLocation();
                        locationObtained = true;
                        console.log('HASIL GPS:', {
                            latitude: location.latitude,
                            longitude: location.longitude,
                            accuracy: location.accuracy
                        });

                        setPill(gpsState, 'TERDETEKSI', 'success');

                        if (gpsDescription) {
                            gpsDescription.textContent =
                                `Koordinat diperoleh. Akurasi ±${Math.round(location.accuracy)} meter.`;
                        }

                        setStatus(
                            'Lokasi berhasil dibaca',
                            'Mengirim data verifikasi absensi ke server.',
                            'success'
                        );

                        const data = await postJson(
                            "{{ route('absen.store') }}", {
                                face_vector: faceVector,
                                latitude: location.latitude,
                                longitude: location.longitude,
                                accuracy: location.accuracy
                            }
                        );

                        setPill(gpsState, 'TERVERIFIKASI', 'success');

                        if (gpsDescription) {
                            gpsDescription.textContent = 'Koordinat diterima oleh server.';
                        }

                        if (data.type === 'pulang') {
                            showResult(
                                'Absen Pulang Berhasil',
                                `${data.data.jam_pulang} WITA`,
                                'Absensi kepulangan Anda berhasil dicatat.'
                            );
                        } else {
                            const status = data.data.status === 'terlambat' ?
                                'Terlambat' :
                                'Hadir';

                            showResult(
                                'Absen Masuk Berhasil',
                                `${data.data.jam_masuk} WITA • ${status}`,
                                'Absensi masuk Anda berhasil dicatat.'
                            );
                        }

                        if (stream) {
                            stream.getTracks().forEach(track => track.stop());
                            stream = null;
                        }

                        if (cameraLabel) {
                            cameraLabel.textContent = 'Absensi berhasil';
                        }

                        setTimeout(() => {
                            window.location.href = "{{ route('dashboard') }}";
                        }, 4000);

                    } catch (error) {
                        console.error('Attendance error:', error);

                        if (!locationObtained) {
                            setPill(gpsState, 'GAGAL', 'error');

                            if (gpsDescription) {
                                gpsDescription.textContent = error.message;
                            }
                        } else {
                            setPill(gpsState, 'TERDETEKSI', 'success');

                            if (gpsDescription) {
                                gpsDescription.textContent =
                                    'Koordinat berhasil diperoleh, tetapi permintaan absensi ditolak atau gagal.';
                            }
                        }

                        processing = false;
                        scanning = false;
                        resetChallenge();

                        setStatus(
                            locationObtained ? 'Penyimpanan absensi gagal' : 'Lokasi gagal diperoleh',
                            error.message || 'Periksa lokasi dan coba lagi.',
                            'error'
                        );

                        setTimeout(() => {
                            if (!processing) {
                                beginAttendanceChallenge();
                            }
                        }, 1800);
                    }
                }

                async function beginAttendanceChallenge() {
                    if (processing) return;

                    try {
                        processing = true;
                        scanning = false;

                        resetChallenge();

                        setPill(livenessState, 'MENUNGGU');
                        setPill(faceState, 'MENUNGGU');

                        setStatus(
                            'Menyiapkan pemeriksaan',
                            'Memulai tantangan wajah...'
                        );

                        await startChallenge();

                        processing = false;
                        scanning = true;

                        requestAnimationFrame(scanFace);

                    } catch (error) {
                        console.error('Challenge error:', error);

                        processing = false;
                        scanning = false;

                        setPill(livenessState, 'GAGAL', 'error');

                        setStatus(
                            'Gagal memulai pemeriksaan',
                            error.message || 'Silakan muat ulang halaman.',
                            'error'
                        );
                    }
                }

                async function init() {
                    try {
                        prepareReferenceFace();

                        setStatus(
                            'Mengaktifkan kamera',
                            'Mohon izinkan akses kamera.'
                        );

                        await startCamera();

                        setStatus(
                            'Memuat model wajah',
                            'Menyiapkan model pengenalan wajah.'
                        );

                        await loadModels();

                        await beginAttendanceChallenge();

                    } catch (error) {
                        console.error('Initialization error:', error);

                        cameraLabel.textContent = 'Kamera atau model gagal';

                        setStatus(
                            'Tidak dapat memulai scan',
                            error.message || 'Periksa kamera dan file model.',
                            'error'
                        );

                        if (stream) {
                            stream.getTracks().forEach(track => track.stop());
                            stream = null;
                        }
                    }
                }

                window.addEventListener('beforeunload', () => {
                    if (stream) {
                        stream.getTracks().forEach(track => track.stop());
                    }
                });

                if (['masuk', 'pulang'].includes(modeScan)) {
                    init();
                }
            </script>
            @endpush