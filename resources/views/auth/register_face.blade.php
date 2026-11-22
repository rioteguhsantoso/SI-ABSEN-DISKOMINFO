
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pendaftaran Wajah - SI-ABSEN DISKOMINFO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="{{ asset('js/face-api.min.js') }}"></script>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .camera-frame {
            position: relative;
            overflow: hidden;
            background: #071426;
        }
        .camera-frame video,
        .camera-frame canvas {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .camera-frame video { transform: scaleX(-1); }
        .camera-frame canvas { pointer-events: none; }
        .face-guide {
            position: absolute;
            z-index: 2;
            width: 180px;
            height: 230px;
            border: 2px solid rgba(56,189,248,.9);
            border-radius: 48% 48% 45% 45%;
            left: 50%;
            top: 50%;
            transform: translate(-50%,-50%);
            box-shadow: 0 0 0 999px rgba(7,20,38,.28);
            pointer-events: none;
        }
        .scan-line {
            position: absolute;
            z-index: 3;
            left: 12%;
            right: 12%;
            height: 2px;
            background: #38bdf8;
            box-shadow: 0 0 12px rgba(56,189,248,.8);
            animation: scan 2.5s ease-in-out infinite;
            pointer-events: none;
        }
        .camera-info { z-index: 4; }
        @keyframes scan {
            0%,100% { top: 25%; opacity: .4; }
            50% { top: 70%; opacity: 1; }
        }
        summary { list-style: none; }
        summary::-webkit-details-marker { display: none; }
    </style>
</head>
<body class="min-h-screen bg-[#eef3f9] px-3 py-6 sm:px-5 sm:py-10">
    <main class="mx-auto w-full max-w-lg">
        {{-- IDENTITAS SISTEM --}}
        <div class="mb-5 flex items-center justify-between gap-3 px-1">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#17365D] shadow-md shadow-blue-900/15">
                    <svg class="h-6 w-6 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-extrabold tracking-wide text-[#17365D]">SI-ABSEN</p>
                    <p class="text-[9px] font-semibold tracking-wider text-slate-500">DISKOMINFO BANJARMASIN</p>
                </div>
            </div>
            <span class="rounded-full border border-blue-100 bg-white px-3 py-1.5 text-[9px] font-bold text-[#17365D]">
                REGISTRASI WAJAH
            </span>
        </div>

        {{-- KARTU UTAMA --}}
        <div class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-xl shadow-slate-300/40">
            {{-- HEADER --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-[#17365D] to-[#0d294e] px-5 py-7 text-center sm:px-8">
                <div class="absolute -right-12 -top-16 h-44 w-44 rounded-full border-[25px] border-white/5"></div>
                <div class="absolute -bottom-20 -left-12 h-48 w-48 rounded-full border-[25px] border-white/5"></div>
                <div class="relative">
                    <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl border border-white/15 bg-white/10 shadow-inner">
                        <svg class="h-8 w-8 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 11a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 0114 0M18 8h3m-1.5-1.5v3"/>
                        </svg>
                    </div>
                    <p class="mb-2 text-[9px] font-bold uppercase tracking-[.2em] text-sky-300">
                        Pemerintah Kota Banjarmasin
                    </p>
                    <h1 class="text-xl font-extrabold tracking-tight text-white sm:text-2xl">
                        Pendaftaran Wajah
                    </h1>
                    <p class="mt-2 text-[11px] leading-relaxed text-blue-100/80">
                        Registrasikan data wajah untuk sistem verifikasi kehadiran pegawai.
                    </p>
                </div>
            </div>

            <div class="space-y-4 p-4 sm:p-6">
                {{-- INFORMASI PEGAWAI --}}
                <div class="rounded-2xl border border-blue-100 bg-blue-50/70 p-3.5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-[#17365D] shadow-sm">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 21a8 8 0 00-16 0m8-10a4 4 0 100-8 4 4 0 000 8z"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[9px] font-bold uppercase tracking-wider text-blue-500">Pegawai terdaftar</p>
                            <p class="mt-0.5 truncate text-sm font-extrabold text-slate-800">{{ $pegawai->nama }}</p>
                            <p class="mt-0.5 text-[10px] text-slate-500">NIP {{ $pegawai->nip }}</p>
                        </div>
                        <span class="shrink-0 rounded-full bg-emerald-100 px-2.5 py-1 text-[9px] font-bold text-emerald-700">
                            TERDAFTAR
                        </span>
                    </div>
                </div>

                {{-- JUDUL AREA KAMERA --}}
                <div class="flex items-center justify-between gap-2">
                    <div>
                        <h2 class="text-xs font-extrabold text-[#17365D]">Pemindaian Wajah</h2>
                        <p class="mt-0.5 text-[9px] text-slate-500">Posisikan wajah di dalam area panduan</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1.5 text-[9px] font-bold text-emerald-700">
                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-emerald-500"></span>
                        FACE SCAN
                    </span>
                </div>

                {{-- ALERT --}}
                <div id="alertBox" class="hidden rounded-xl border p-3 text-xs font-medium" role="alert" aria-live="polite"></div>

                {{-- KAMERA --}}
                <div class="camera-frame aspect-[4/5] w-full rounded-2xl border border-slate-200 shadow-inner">
                    <video id="video" autoplay muted playsinline></video>
                    <canvas id="landmarkCanvas"></canvas>
                    <div class="face-guide"></div>
                    <div class="scan-line"></div>

                    <div class="camera-info absolute left-3 right-3 top-3 flex items-center justify-between gap-2">
                        <span id="cameraStatus" class="rounded-lg bg-slate-950/75 px-2.5 py-1.5 text-[9px] font-bold text-white">
                            Menyiapkan kamera...
                        </span>
                        <span id="faceStatus" class="rounded-lg bg-slate-950/75 px-2.5 py-1.5 text-[9px] font-bold text-white">
                            Menunggu wajah...
                        </span>
                    </div>

                    {{-- LOADING --}}
                    <div id="loadingOverlay" class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-[#071426]/90 text-white">
                        <svg class="mb-3 h-9 w-9 animate-spin text-sky-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v3a5 5 0 00-5 5H4z"></path>
                        </svg>
                        <p id="loadingText" class="text-xs font-bold">Mengaktifkan kamera...</p>
                        <p class="mt-1 text-[9px] text-slate-400">Mohon tunggu sebentar</p>
                    </div>

                    <div class="camera-info absolute bottom-3 left-3 right-3 flex justify-center">
                        <span class="rounded-full border border-white/10 bg-slate-950/65 px-3 py-1.5 text-[9px] font-medium text-white/90">
                            Pastikan wajah terlihat jelas
                        </span>
                    </div>
                </div>

                {{-- PETUNJUK --}}
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3.5">
                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-[#17365D] shadow-sm">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16a2 2 0 001.73 3z"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-extrabold text-slate-800">Petunjuk Pendaftaran</p>
                            <p id="instruction" class="mt-1 text-[10px] leading-relaxed text-slate-500">
                                Izinkan akses kamera, hadapkan wajah ke kamera, dan pastikan pencahayaan cukup.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- TOMBOL REGISTRASI --}}
                <button id="registerButton" type="button" disabled class="flex w-full cursor-not-allowed items-center justify-center gap-2 rounded-xl bg-slate-300 py-3.5 text-sm font-extrabold text-white transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 5v14m-7-7h14"/>
                    </svg>
                    Daftarkan Wajah
                </button>

                {{-- CATATAN --}}
                <div class="flex items-start gap-2 rounded-xl bg-blue-50/70 px-3 py-2.5">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8h.01M11 12h1v4h1m8-4a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-[9px] leading-relaxed text-slate-600">
                        Setelah menekan tombol, ikuti instruksi gerakan kepala untuk menyelesaikan pemeriksaan keaslian wajah.
                    </p>
                </div>

                {{-- KEMBALI KE LOGIN --}}
                <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 py-1 text-[11px] font-bold text-slate-500 transition hover:text-[#17365D]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 19l-7-7 7-7M3 12h18"/>
                    </svg>
                    Kembali ke Login
                </a>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="mt-5 text-center">
            <p class="text-[9px] font-semibold tracking-wide text-slate-400">
                SI-ABSEN VERSI 2.4.0
            </p>
            <p class="mt-1 text-[9px] text-slate-400">
                Dinas Komunikasi, Informatika dan Statistik Kota Banjarmasin
            </p>
        </div>
    </main>

    <script>

        const video = document.getElementById('video');
        const canvas = document.getElementById('landmarkCanvas');
        const ctx = canvas.getContext('2d');
        const registerButton = document.getElementById('registerButton');
        const cameraStatus = document.getElementById('cameraStatus');
        const faceStatus = document.getElementById('faceStatus');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const loadingText = document.getElementById('loadingText');
        const instruction = document.getElementById('instruction');
        const alertBox = document.getElementById('alertBox');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        let latestDescriptor = null;
        let detecting = false;
        let stream = null;
        let registrationInProgress = false;
        let challengeActive = false;
        let challengeToken = null;
        let challengeSteps = [];
        let completedSteps = [];
        let challengeDescriptors = [];
        let challengeIndex = 0;
        let stableFrames = 0;
        let savedDescriptor = null;
        let animationScheduled = false;
        let modelsReady = false;
        let challengeSubmitting = false;

        function showAlert(message, type = 'error') {
            alertBox.className = 'mb-4 rounded-xl border p-3 text-xs font-medium';
            alertBox.classList.add(...(type === 'success'
                ? ['bg-emerald-50', 'border-emerald-200', 'text-emerald-700']
                : ['bg-rose-50', 'border-rose-200', 'text-rose-600']));
            alertBox.textContent = message;
            alertBox.classList.remove('hidden');
        }

        async function postJson(url, data) {
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

        async function startCamera() {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                throw new Error('Browser tidak mendukung kamera. Gunakan localhost atau HTTPS.');
            }

            stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user',
                    width: { ideal: 640 },
                    height: { ideal: 480 }
                },
                audio: false
            });

            video.srcObject = stream;

            await new Promise((resolve, reject) => {
                if (video.readyState >= 1) {
                    resolve();
                    return;
                }

                video.onloadedmetadata = () => resolve();
                video.onerror = () => reject(new Error('Kamera gagal dimuat.'));
            });

            await video.play();

            cameraStatus.textContent = 'Kamera aktif';
            cameraStatus.className = 'px-2.5 py-1.5 bg-emerald-500/90 text-white rounded-lg text-[9px] font-bold';

            loadingText.textContent = 'Memuat model deteksi wajah...';
            loadingOverlay.classList.add('hidden');
        }

        async function loadModels() {
            if (typeof faceapi === 'undefined') {
                throw new Error('Library face-api.js tidak ditemukan. Periksa public/js/face-api.min.js.');
            }

            const modelUrl = "{{ asset('models') }}";

            await Promise.all([
                faceapi.nets.ssdMobilenetv1.loadFromUri(modelUrl),
                faceapi.nets.faceLandmark68Net.loadFromUri(modelUrl),
                faceapi.nets.faceRecognitionNet.loadFromUri(modelUrl)
            ]);

            modelsReady = true;
        }

        function drawLandmarks(landmarks) {
            if (!video.videoWidth || !video.videoHeight) return;

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            ctx.clearRect(0, 0, canvas.width, canvas.height);

            const mirrorX = x => canvas.width - x;

            const paths = [
                landmarks.getJawOutline(),
                landmarks.getLeftEyeBrow(),
                landmarks.getRightEyeBrow(),
                landmarks.getNose(),
                landmarks.getLeftEye(),
                landmarks.getRightEye(),
                landmarks.getMouth()
            ];

            ctx.save();
            ctx.strokeStyle = '#38bdf8';
            ctx.lineWidth = Math.max(1.5, canvas.width / 320);
            ctx.shadowColor = '#22d3ee';
            ctx.shadowBlur = 5;

            paths.forEach(points => {
                if (!points || !points.length) return;

                ctx.beginPath();

                points.forEach((point, index) => {
                    const x = mirrorX(point.x);

                    if (index === 0) {
                        ctx.moveTo(x, point.y);
                    } else {
                        ctx.lineTo(x, point.y);
                    }
                });

                if (points.length > 2) ctx.closePath();
                ctx.stroke();
            });

            ctx.fillStyle = '#a3e635';

            landmarks.positions.forEach(point => {
                ctx.beginPath();
                ctx.arc(
                    mirrorX(point.x),
                    point.y,
                    Math.max(1.5, canvas.width / 240),
                    0,
                    Math.PI * 2
                );
                ctx.fill();
            });

            ctx.restore();
        }

        function setRegisterEnabled(enabled) {
            registerButton.disabled = !enabled || registrationInProgress;

            registerButton.className = enabled && !registrationInProgress
                ? 'flex w-full items-center justify-center gap-2 rounded-xl bg-[#17365D] py-3.5 text-sm font-extrabold text-white shadow-lg shadow-blue-900/15 transition hover:bg-[#0c2752]'
                : 'flex w-full cursor-not-allowed items-center justify-center gap-2 rounded-xl bg-slate-300 py-3.5 text-sm font-extrabold text-white transition';
        }

        function setButtonText(text, spinning = false) {
            registerButton.textContent = spinning ? `⏳ ${text}` : text;
        }

        function getStepInstruction(step) {
            if (step === 'left') {
                return 'Tantangan: putar kepala ke kiri Anda, tahan sebentar, lalu kembali menghadap kamera.';
            }

            if (step === 'right') {
                return 'Tantangan: putar kepala ke kanan Anda, tahan sebentar, lalu kembali menghadap kamera.';
            }

            return 'Gerakan tidak dikenal. Silakan ulangi.';
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
        }

        function processChallengeFrame(detection) {
            if (!challengeActive || challengeIndex >= challengeSteps.length) return;

            const step = challengeSteps[challengeIndex];
            const box = detection.detection.box;
            const nose = detection.landmarks.positions[30];
            const ratio = (nose.x - box.x) / box.width;

            let completed = false;

            if (step === 'left') completed = ratio > 0.56;
            if (step === 'right') completed = ratio < 0.44;

            stableFrames = completed ? stableFrames + 1 : 0;

            if (stableFrames < 3) return;

            completedSteps.push(step);
            challengeDescriptors.push(Array.from(detection.descriptor));

            challengeIndex++;
            stableFrames = 0;

            if (challengeIndex < challengeSteps.length) {
                instruction.textContent = getStepInstruction(challengeSteps[challengeIndex]);

                showAlert(
                    `Gerakan ${completedSteps.length} dari ${challengeSteps.length} berhasil. Lanjutkan instruksi berikutnya.`,
                    'success'
                );
            } else {
                challengeActive = false;

                if (!challengeSubmitting) {
                    challengeSubmitting = true;
                    instruction.textContent = 'Semua gerakan terdeteksi. Memverifikasi wajah...';
                    verifyAndSave();
                }
            }
        }

        async function detectFace() {
            if (detecting || !modelsReady || video.readyState < 2) {
                scheduleDetection();
                return;
            }

            detecting = true;

            try {
                const detection = await faceapi
                    .detectSingleFace(
                        video,
                        new faceapi.SsdMobilenetv1Options({ minConfidence: 0.5 })
                    )
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (detection) {
                    latestDescriptor = Array.from(detection.descriptor);

                    drawLandmarks(detection.landmarks);

                    faceStatus.textContent = challengeActive
                        ? 'Pemeriksaan wajah'
                        : 'Wajah terdeteksi';

                    faceStatus.className = 'px-2.5 py-1.5 bg-emerald-500/90 text-white rounded-lg text-[9px] font-bold';

                    if (challengeActive) {
                        processChallengeFrame(detection);
                    } else {
                        instruction.textContent = 'Titik hijau menunjukkan titik wajah. Pastikan wajah berada di dalam panduan.';
                        setRegisterEnabled(Boolean(latestDescriptor));
                    }
                } else {
                    latestDescriptor = null;

                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    faceStatus.textContent = 'Mencari wajah...';
                    faceStatus.className = 'px-2.5 py-1.5 bg-slate-950/75 text-white rounded-lg text-[9px] font-bold';

                    if (challengeActive) {
                        stableFrames = 0;
                        instruction.textContent = 'Wajah tidak terlihat. Posisikan wajah kembali di dalam panduan.';
                    } else {
                        instruction.textContent = 'Posisikan seluruh wajah di dalam garis panduan dan pastikan pencahayaan cukup.';
                        setRegisterEnabled(false);
                    }
                }
            } catch (error) {
                console.error('Deteksi wajah gagal:', error);

                faceStatus.textContent = 'Deteksi bermasalah';

                if (!challengeActive) {
                    setRegisterEnabled(false);
                }
            } finally {
                detecting = false;
                scheduleDetection();
            }
        }

        function scheduleDetection() {
            if (animationScheduled) return;

            animationScheduled = true;

            requestAnimationFrame(() => {
                animationScheduled = false;

                if (stream && stream.active && modelsReady) {
                    detectFace();
                }
            });
        }

        async function verifyAndSave() {
            try {
                if (
                    !challengeToken ||
                    completedSteps.length !== 2 ||
                    challengeDescriptors.length !== 2
                ) {
                    throw new Error('Pemeriksaan wajah belum lengkap. Silakan ulangi.');
                }

                await postJson("{{ route('face.registration.challenge.verify') }}", {
                    token: challengeToken,
                    purpose: 'registration',
                    completed_steps: completedSteps,
                    descriptors: challengeDescriptors
                });

                const result = await postJson("{{ route('pegawai.store') }}", {
                    face_vector: JSON.stringify(savedDescriptor)
                });

                registrationInProgress = true;

                showAlert(
                    result.message || 'Wajah berhasil didaftarkan.',
                    'success'
                );

                setButtonText('Wajah Berhasil Didaftarkan');
                registerButton.disabled = true;

                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }

                setTimeout(() => {
                    window.location.href = "{{ route('login') }}";
                }, 1500);

            } catch (error) {
                console.error('Gagal menyimpan wajah:', error);

                resetChallenge();
                registrationInProgress = false;

                setButtonText('Daftarkan Wajah');

                showAlert(
                    error.message || 'Pemeriksaan wajah gagal. Silakan ulangi.'
                );

                instruction.textContent = 'Pastikan wajah terlihat jelas, lalu coba kembali.';

                setRegisterEnabled(Boolean(latestDescriptor));
            }
        }

        registerButton.addEventListener('click', async () => {
            if (!latestDescriptor || registrationInProgress || !modelsReady) {
                showAlert('Kamera dan deteksi wajah harus siap terlebih dahulu.');
                return;
            }

            registrationInProgress = true;
            savedDescriptor = [...latestDescriptor];

            setRegisterEnabled(false);
            setButtonText('Memulai pemeriksaan...', true);

            showAlert('Menyiapkan pemeriksaan wajah...', 'success');

            try {
                const result = await postJson(
                    "{{ route('face.registration.challenge.start') }}",
                    {
                        purpose: 'registration'
                    }
                );

                if (
                    !result.challenge?.token ||
                    !Array.isArray(result.challenge.steps) ||
                    result.challenge.steps.length !== 2
                ) {
                    throw new Error(
                        'Tantangan pendaftaran harus berisi dua langkah. Periksa controller.'
                    );
                }

                if (!result.challenge.steps.every(step => ['left', 'right'].includes(step))) {
                    throw new Error('Server mengirim langkah pemeriksaan yang tidak dikenal.');
                }

                challengeToken = result.challenge.token;
                challengeSteps = result.challenge.steps;
                completedSteps = [];
                challengeDescriptors = [];
                challengeIndex = 0;
                stableFrames = 0;
                challengeActive = true;
                challengeSubmitting = false;

                setButtonText('Ikuti instruksi...', true);

                instruction.textContent = getStepInstruction(challengeSteps[0]);

                showAlert(
                    'Pemeriksaan dimulai. Ikuti instruksi di bawah kamera.',
                    'success'
                );

            } catch (error) {
                console.error('Gagal memulai tantangan:', error);

                resetChallenge();
                registrationInProgress = false;

                setButtonText('Daftarkan Wajah');

                showAlert(
                    error.message || 'Gagal memulai pemeriksaan wajah.'
                );

                setRegisterEnabled(Boolean(latestDescriptor));
            }
        });

        async function initialize() {
            try {
                loadingText.textContent = 'Mengaktifkan kamera...';

                await startCamera();

            } catch (error) {
                console.error('Kamera gagal dibuka:', error);

                cameraStatus.textContent = 'Kamera tidak siap';

                loadingOverlay.classList.add('hidden');

                showAlert(
                    error.message || 'Tidak dapat mengakses kamera. Periksa izin kamera browser.'
                );

                instruction.textContent = 'Izinkan kamera melalui pengaturan browser, lalu muat ulang halaman.';

                return;
            }

            try {
                loadingText.textContent = 'Memuat model deteksi wajah...';

                await loadModels();

                loadingOverlay.classList.add('hidden');

                instruction.textContent = 'Posisikan wajah di dalam garis panduan.';

                detectFace();

            } catch (error) {
                console.error('Model wajah gagal dimuat:', error);

                showAlert(
                    'Kamera aktif, tetapi model wajah gagal dimuat. Periksa file model di public/models.'
                );

                instruction.textContent = 'Pastikan file model face-api.js tersedia di folder public/models.';

                faceStatus.textContent = 'Model belum siap';
            }
        }

        window.addEventListener('beforeunload', () => {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
        });

        initialize();
    </script>
</body>
</html>
