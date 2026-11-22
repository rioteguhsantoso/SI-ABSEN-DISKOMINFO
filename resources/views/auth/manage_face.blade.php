<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Data Wajah - SI-ABSEN DISKOMINFO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="{{ asset('js/face-api.min.js') }}"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif
        }

        .camera-frame {
            position: relative;
            overflow: hidden;
            background: #0f172a
        }

        .camera-frame video,
        .camera-frame canvas {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .camera-frame video {
            transform: scaleX(-1)
        }

        .camera-frame canvas {
            pointer-events: none
        }

        .face-guide {
            position: absolute;
            z-index: 2;
            width: 180px;
            height: 230px;
            border: 2px solid rgba(56, 189, 248, .9);
            border-radius: 48% 48% 45% 45%;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0 0 0 999px rgba(15, 23, 42, .25);
            pointer-events: none
        }

        .scan-line {
            position: absolute;
            z-index: 3;
            left: 12%;
            right: 12%;
            height: 2px;
            background: rgba(56, 189, 248, .9);
            box-shadow: 0 0 12px rgba(56, 189, 248, .8);
            animation: scan 2.2s ease-in-out infinite;
            pointer-events: none
        }

        .camera-info {
            z-index: 4
        }

        @keyframes scan {

            0%,
            100% {
                top: 25%;
                opacity: .4
            }

            50% {
                top: 70%;
                opacity: 1
            }
        }
    </style>
</head>

<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-3xl p-5 sm:p-7 shadow-2xl my-4">
        <div class="flex flex-col items-center text-center mb-5">
            <div class="w-14 h-14 bg-[#0f2d5c] rounded-2xl flex items-center justify-center shadow-lg mb-3">
                <svg class="w-8 h-8 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3zm-7 8a7 7 0 0114 0" />
                </svg>
            </div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-sky-50 border border-sky-100 rounded-full text-[10px] font-bold text-sky-700 tracking-wide uppercase mb-2">
                <span>🏛️</span> PEMERINTAH KOTA BANJARMASIN
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Kelola Data Wajah</h1>
            <p class="text-slate-500 text-xs mt-1">Perbarui data wajah untuk verifikasi absensi Anda</p>
        </div>

        <div class="bg-blue-50/70 border border-blue-100 rounded-2xl p-3.5 mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-blue-900 shadow-sm shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-6 0h6" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] text-slate-500 font-semibold uppercase tracking-wide">Pegawai</p>
                    <p class="text-sm font-bold text-slate-900 truncate">{{ $pegawai->nama }}</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">NIP {{ $pegawai->nip }}</p>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between gap-3 bg-slate-50 border border-slate-200 rounded-xl p-3 mb-4">
            <div>
                <p class="text-[10px] text-slate-500 font-semibold uppercase">Status Data Wajah</p>
                <p class="text-xs font-bold text-slate-800 mt-1">
                    {{ $wajahTerdaftar ? 'Wajah sudah terdaftar' : 'Wajah belum terdaftar' }}
                </p>
            </div>
            <span class="px-3 py-1.5 rounded-lg text-[10px] font-bold {{ $wajahTerdaftar ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                {{ $wajahTerdaftar ? 'TERDAFTAR' : 'BELUM TERDAFTAR' }}
            </span>
        </div>

        <div id="alertBox" class="hidden mb-4 p-3 rounded-xl text-xs font-medium" role="alert" aria-live="polite"></div>

        <div class="camera-frame w-full aspect-[4/5] rounded-2xl border border-slate-200 shadow-inner">
            <video id="video" autoplay muted playsinline></video>
            <canvas id="landmarkCanvas"></canvas>
            <div class="face-guide"></div>
            <div class="scan-line"></div>
            <div class="camera-info absolute top-3 left-3 right-3 flex items-center justify-between gap-2">
                <span id="cameraStatus" class="px-2.5 py-1.5 bg-slate-900/75 text-white rounded-lg text-[10px] font-semibold">Menyiapkan kamera...</span>
                <span id="faceStatus" class="px-2.5 py-1.5 bg-slate-900/75 text-white rounded-lg text-[10px] font-semibold">Menunggu wajah...</span>
            </div>
            <div id="loadingOverlay" class="absolute inset-0 z-10 bg-slate-900/85 flex flex-col items-center justify-center text-white">
                <svg class="w-8 h-8 animate-spin mb-3 text-sky-400" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v3a5 5 0 00-5 5H4z"></path>
                </svg>
                <p id="loadingText" class="text-xs font-semibold">Mengaktifkan kamera...</p>
            </div>
        </div>

        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-3.5 mt-4">
            <div class="flex gap-3">
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16a2 2 0 001.73 3z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-800">Petunjuk pemeriksaan wajah</p>
                    <p id="instruction" class="text-[11px] text-slate-500 leading-relaxed mt-0.5">Izinkan akses kamera dan pastikan pencahayaan cukup.</p>
                </div>
            </div>
        </div>

        <button id="updateButton" type="button" disabled class="w-full mt-4 py-3.5 bg-slate-300 text-white font-bold rounded-xl cursor-not-allowed transition flex items-center justify-center gap-2 text-sm">
            Perbarui Data Wajah
        </button>

        <a href="{{ url('/profil') }}" class="block text-center mt-4 text-xs font-semibold text-slate-500 hover:text-slate-800">Kembali ke Profil</a>

        <div class="mt-5 text-center text-[10px] text-slate-400">SI-ABSEN Versi 2.4.0 • Diskominfotik Kota Banjarmasin</div>
    </div>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('landmarkCanvas');
        const ctx = canvas.getContext('2d');
        const updateButton = document.getElementById('updateButton');
        const cameraStatus = document.getElementById('cameraStatus');
        const faceStatus = document.getElementById('faceStatus');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const loadingText = document.getElementById('loadingText');
        const instruction = document.getElementById('instruction');
        const alertBox = document.getElementById('alertBox');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        let latestDescriptor = null;
        let savedDescriptor = null;
        let stream = null;
        let detecting = false;
        let animationScheduled = false;
        let modelsReady = false;
        let updateInProgress = false;
        let challengeActive = false;
        let challengeSubmitting = false;
        let challengeToken = null;
        let challengeSteps = [];
        let completedSteps = [];
        let challengeDescriptors = [];
        let challengeIndex = 0;
        let stableFrames = 0;

        function showAlert(message, type = 'error') {
            alertBox.className = 'mb-4 p-3 rounded-xl text-xs font-medium';
            alertBox.classList.add(...(type === 'success' ?
                ['bg-emerald-50', 'border', 'border-emerald-200', 'text-emerald-700'] :
                ['bg-rose-50', 'border', 'border-rose-200', 'text-rose-600']));
            alertBox.textContent = message;
            alertBox.classList.remove('hidden');
        }

        async function postJson(url, data) {
            const response = await fetch(url, {
                method: 'POST',
                credentials: 'same-origin',
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
                    width: {
                        ideal: 640
                    },
                    height: {
                        ideal: 480
                    }
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
            cameraStatus.className = 'px-2.5 py-1.5 bg-emerald-500/90 text-white rounded-lg text-[10px] font-semibold';
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
                    if (index === 0) ctx.moveTo(x, point.y);
                    else ctx.lineTo(x, point.y);
                });
                if (points.length > 2) ctx.closePath();
                ctx.stroke();
            });
            ctx.fillStyle = '#a3e635';
            landmarks.positions.forEach(point => {
                ctx.beginPath();
                ctx.arc(mirrorX(point.x), point.y, Math.max(1.5, canvas.width / 240), 0, Math.PI * 2);
                ctx.fill();
            });
            ctx.restore();
        }

        function setButtonEnabled(enabled) {
            updateButton.disabled = !enabled || updateInProgress || challengeActive;
            updateButton.className = enabled && !updateInProgress && !challengeActive ?
                'w-full mt-4 py-3.5 bg-[#123873] hover:bg-[#0c2752] text-white font-bold rounded-xl shadow-lg transition flex items-center justify-center gap-2 text-sm' :
                'w-full mt-4 py-3.5 bg-slate-300 text-white font-bold rounded-xl cursor-not-allowed transition flex items-center justify-center gap-2 text-sm';
        }

        function setButtonText(text, spinning = false) {
            updateButton.textContent = spinning ? `⏳ ${text}` : text;
        }

        function getStepInstruction(step) {
            if (step === 'left') return 'Tantangan: putar kepala ke kiri Anda, tahan sebentar, lalu kembali menghadap kamera.';
            if (step === 'right') return 'Tantangan: putar kepala ke kanan Anda, tahan sebentar, lalu kembali menghadap kamera.';
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
                showAlert(`Gerakan ${completedSteps.length} dari ${challengeSteps.length} berhasil. Lanjutkan instruksi berikutnya.`, 'success');
            } else {
                challengeActive = false;
                if (!challengeSubmitting) {
                    challengeSubmitting = true;
                    instruction.textContent = 'Semua gerakan terdeteksi. Memverifikasi wajah...';
                    verifyAndUpdate();
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
                        new faceapi.SsdMobilenetv1Options({
                            minConfidence: 0.5
                        })
                    )
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (detection) {
                    latestDescriptor = Array.from(detection.descriptor);
                    drawLandmarks(detection.landmarks);
                    faceStatus.textContent = challengeActive ? 'Pemeriksaan wajah' : 'Wajah terdeteksi';
                    faceStatus.className = 'px-2.5 py-1.5 bg-emerald-500/90 text-white rounded-lg text-[10px] font-semibold';
                    if (challengeActive) {
                        processChallengeFrame(detection);
                    } else {
                        instruction.textContent = 'Wajah terdeteksi. Pastikan wajah berada di dalam panduan sebelum memperbarui data.';
                        setButtonEnabled(Boolean(latestDescriptor));
                    }
                } else {
                    latestDescriptor = null;
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    faceStatus.textContent = 'Mencari wajah...';
                    faceStatus.className = 'px-2.5 py-1.5 bg-slate-900/75 text-white rounded-lg text-[10px] font-semibold';
                    if (challengeActive) {
                        stableFrames = 0;
                        instruction.textContent = 'Wajah tidak terlihat. Posisikan wajah kembali di dalam panduan.';
                    } else {
                        instruction.textContent = 'Posisikan seluruh wajah di dalam garis panduan dan pastikan pencahayaan cukup.';
                        setButtonEnabled(false);
                    }
                }
            } catch (error) {
                console.error('Deteksi wajah gagal:', error);
                faceStatus.textContent = 'Deteksi bermasalah';
                if (!challengeActive) setButtonEnabled(false);
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
                if (stream && stream.active && modelsReady) detectFace();
            });
        }

        async function verifyAndUpdate() {
            try {
                if (!challengeToken || completedSteps.length !== 2 || challengeDescriptors.length !== 2 || !savedDescriptor) {
                    throw new Error('Pemeriksaan wajah belum lengkap. Silakan ulangi.');
                }

                await postJson("{{ route('face.update.challenge.verify') }}", {
                    token: challengeToken,
                    purpose: 'face_update',
                    completed_steps: completedSteps,
                    descriptors: challengeDescriptors
                });

                const result = await postJson("{{ route('face.update.store') }}", {
                    face_vector: savedDescriptor
                });

                updateInProgress = true;
                showAlert(result.message || 'Data wajah berhasil diperbarui.', 'success');
                setButtonText('Data Wajah Berhasil Diperbarui');
                updateButton.disabled = true;
                instruction.textContent = 'Data wajah baru sudah tersimpan. Anda akan diarahkan kembali ke halaman Profil.';
                stopCamera();

                setTimeout(() => {
                    window.location.href = "{{ url('/profil') }}";
                }, 1800);
            } catch (error) {
                console.error('Gagal memperbarui wajah:', error);
                resetChallenge();
                updateInProgress = false;
                savedDescriptor = null;
                setButtonText('Perbarui Data Wajah');
                showAlert(error.message || 'Pemeriksaan wajah gagal. Silakan ulangi.');
                instruction.textContent = 'Pastikan wajah terlihat jelas, lalu coba kembali.';
                setButtonEnabled(Boolean(latestDescriptor));
            }
        }

        updateButton.addEventListener('click', async () => {
            if (!latestDescriptor || updateInProgress || !modelsReady || challengeActive) {
                showAlert('Kamera dan deteksi wajah harus siap terlebih dahulu.');
                return;
            }

            updateInProgress = true;
            savedDescriptor = [...latestDescriptor];
            setButtonEnabled(false);
            setButtonText('Memulai pemeriksaan...', true);
            showAlert('Menyiapkan pemeriksaan wajah...', 'success');

            try {
                const result = await postJson("{{ route('face.update.challenge.start') }}", {
                    purpose: 'face_update'
                });

                if (!result.challenge?.token || !Array.isArray(result.challenge.steps) || result.challenge.steps.length !== 2) {
                    throw new Error('Tantangan harus berisi dua langkah. Periksa controller.');
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
                showAlert('Pemeriksaan dimulai. Ikuti instruksi gerakan kepala.', 'success');
            } catch (error) {
                console.error('Gagal memulai tantangan:', error);
                resetChallenge();
                updateInProgress = false;
                savedDescriptor = null;
                setButtonText('Perbarui Data Wajah');
                showAlert(error.message || 'Gagal memulai pemeriksaan wajah.');
                setButtonEnabled(Boolean(latestDescriptor));
            }
        });

        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            video.srcObject = null;
        }

        async function initialize() {
            try {
                loadingText.textContent = 'Mengaktifkan kamera...';
                await startCamera();
            } catch (error) {
                console.error('Kamera gagal dibuka:', error);
                cameraStatus.textContent = 'Kamera tidak siap';
                loadingOverlay.classList.add('hidden');
                showAlert(error.message || 'Tidak dapat mengakses kamera. Periksa izin kamera browser.');
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
                loadingOverlay.classList.add('hidden');
                showAlert('Kamera aktif, tetapi model wajah gagal dimuat. Periksa file model di public/models.');
                instruction.textContent = 'Pastikan file model face-api.js tersedia di folder public/models.';
                faceStatus.textContent = 'Model belum siap';
            }
        }

        window.addEventListener('beforeunload', stopCamera);
        initialize();
    </script>
</body>

</html>