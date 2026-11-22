@extends('layouts.app')
@section('title', 'Edit Profil - SI-ABSEN DISKOMINFO')
@section('page_title', 'Edit Profil')
@section('content')
<div class="px-4 pt-4 pb-8">
    <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="profileForm">
        @csrf
        @method('PUT')
        {{-- HEADER --}}
        <div class="relative overflow-hidden bg-[#102a45] rounded-[26px] shadow-lg">
            <div class="absolute -top-16 -right-16 w-40 h-40 rounded-full bg-white/[0.04]"></div>
            <div class="absolute -bottom-20 -left-12 w-36 h-36 rounded-full bg-blue-400/[0.05]"></div>
            <div class="relative p-5">
                <div class="flex items-center gap-3">
                    <a href="{{ route('profil') }}" class="w-9 h-9 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <p class="text-[8px] text-blue-200 font-semibold uppercase tracking-[0.14em]">Pengaturan</p>
                        <h2 class="text-[16px] font-extrabold text-white mt-0.5">Edit Profil</h2>
                    </div>
                </div>
            </div>
        </div>
        {{-- FOTO PROFIL --}}
        <div class="bg-white rounded-[22px] border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-3.5 pt-3.5 pb-2.5">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-[#123873]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.5-4.5a2 2 0 012.8 0L15 15l2-2a2 2 0 012.8 0L21 14m-3-9H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[8px] text-slate-400 font-semibold uppercase tracking-[0.12em]">Profil</p>
                        <h3 class="text-[13px] font-extrabold text-slate-900 mt-0.5">Foto Profil</h3>
                    </div>
                </div>
            </div>
            <div class="px-3.5 pb-4">
                <div class="flex items-center gap-4 pt-2.5 border-t border-slate-100">
                    <div class="relative shrink-0">
                        <div class="w-[72px] h-[72px] rounded-[22px] bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center">
                            @if(!empty($pegawai->foto))
                            <img id="previewFoto" src="{{ asset('storage/' . $pegawai->foto) }}" alt="Foto Profil" class="w-full h-full object-cover">
                            <svg id="defaultFoto" class="w-8 h-8 text-slate-400 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M15 19a4 4 0 00-6 0m6-10a3 3 0 11-6 0 3 3 0 016 0zm6 10a7 7 0 00-14 0" />
                            </svg>
                            @else
                            <img id="previewFoto" src="" alt="Preview Foto" class="w-full h-full object-cover hidden">
                            <svg id="defaultFoto" class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M15 19a4 4 0 00-6 0m6-10a3 3 0 11-6 0 3 3 0 016 0zm6 10a7 7 0 00-14 0" />
                            </svg>
                            @endif
                        </div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] font-bold text-slate-800">Ganti Foto</p>
                        <p class="text-[8px] text-slate-400 mt-0.5">Pilih, atur posisi, lalu simpan</p>
                        <label for="foto" class="inline-flex items-center gap-1.5 mt-2 px-3 py-1.5 rounded-lg bg-[#123873] text-white text-[8px] font-bold cursor-pointer">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h4l2-2h6l2 2h4v12H3V7z" />
                                <circle cx="12" cy="13" r="3" />
                            </svg>
                            Pilih Foto
                        </label>
                        <input id="foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp" class="hidden">
                        <p class="text-[7px] text-slate-400 mt-1.5">JPG, PNG, WEBP • Maks. 2 MB</p>
                    </div>
                </div>
                @error('foto')
                <p class="text-[8px] text-red-500 mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>
        {{-- DATA PRIBADI --}}
        <div class="bg-white rounded-[22px] border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-3.5 pt-3.5 pb-2.5">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-[#123873]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM5 21a7 7 0 0114 0" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[8px] text-slate-400 font-semibold uppercase tracking-[0.12em]">Informasi</p>
                        <h3 class="text-[13px] font-extrabold text-slate-900 mt-0.5">Data Pribadi</h3>
                    </div>
                </div>
            </div>
            <div class="px-3.5 pb-1">
                {{-- NAMA --}}
                <div class="py-2.5 border-t border-slate-100">
                    <label for="nama" class="block text-[9px] font-semibold text-slate-500 mb-1.5">Nama Lengkap</label>
                    <input id="nama" name="nama" type="text" value="{{ old('nama', $pegawai->nama) }}" class="w-full h-10 px-3 rounded-xl border border-slate-200 bg-slate-50 text-[11px] font-semibold text-slate-800 outline-none focus:border-blue-400 focus:bg-white transition" required>
                    @error('nama')
                    <p class="text-[8px] text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                {{-- NIP --}}
                <div class="py-2.5 border-t border-slate-100">
                    <label class="block text-[9px] font-semibold text-slate-500 mb-1.5">NIP</label>
                    <div class="w-full h-10 px-3 rounded-xl border border-slate-100 bg-slate-100 flex items-center">
                        <span class="text-[11px] font-semibold text-slate-400">{{ $pegawai->nip }}</span>
                    </div>
                    <p class="text-[7px] text-slate-400 mt-1">NIP tidak dapat diubah.</p>
                </div>
                {{-- EMAIL --}}
                <div class="py-2.5 border-t border-slate-100">
                    <label class="block text-[9px] font-semibold text-slate-500 mb-1.5">Email Akun</label>
                    <div class="w-full h-10 px-3 rounded-xl border border-slate-100 bg-slate-100 flex items-center">
                        <span class="text-[11px] font-semibold text-slate-400 truncate">{{ $user->email }}</span>
                    </div>
                    <p class="text-[7px] text-slate-400 mt-1">Email login tidak diubah dari halaman ini.</p>
                </div>
                {{-- NOMOR HP --}}
                <div class="py-2.5 border-t border-slate-100">
                    <label for="nomor_hp" class="block text-[9px] font-semibold text-slate-500 mb-1.5">Nomor HP</label>
                    <input id="nomor_hp" name="nomor_hp" type="tel" value="{{ old('nomor_hp', $pegawai->nomor_hp) }}" placeholder="Contoh: 081234567890" class="w-full h-10 px-3 rounded-xl border border-slate-200 bg-slate-50 text-[11px] font-semibold text-slate-800 outline-none focus:border-blue-400 focus:bg-white transition">
                    @error('nomor_hp')
                    <p class="text-[8px] text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                {{-- ALAMAT --}}
                <div class="py-2.5 border-t border-slate-100">
                    <label for="alamat" class="block text-[9px] font-semibold text-slate-500 mb-1.5">Alamat Rumah</label>
                    <textarea id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat rumah..." class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-[11px] font-semibold text-slate-800 outline-none focus:border-blue-400 focus:bg-white transition resize-none">{{ old('alamat', $pegawai->alamat) }}</textarea>
                    @error('alamat')
                    <p class="text-[8px] text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        {{-- TOMBOL --}}
        <div class="grid grid-cols-2 gap-2.5">
            <a href="{{ route('profil') }}" class="h-11 rounded-xl border border-slate-200 bg-white flex items-center justify-center text-[10px] font-bold text-slate-600 shadow-sm">Batal</a>
            <button type="submit" class="h-11 rounded-xl bg-[#123873] text-white flex items-center justify-center gap-2 text-[10px] font-bold shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
{{-- CROP MODAL --}}
<div id="cropModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/70 p-4">
    <div class="w-full max-w-[380px] bg-white rounded-[26px] shadow-2xl overflow-hidden">
        <div class="px-4 py-3.5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-[8px] text-slate-400 font-semibold uppercase tracking-[0.12em]">Foto Profil</p>
                <h3 class="text-[14px] font-extrabold text-slate-900 mt-0.5">Atur Foto</h3>
            </div>
            <button type="button" id="closeCrop" class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>
        </div>
        <div class="p-4">
            <div id="cropArea" class="relative mx-auto w-full max-w-[320px] aspect-square rounded-[22px] bg-slate-900 overflow-hidden select-none touch-none">
                <img id="cropImage" src="" alt="Foto" class="absolute max-w-none pointer-events-none select-none" draggable="false">
                <div class="absolute inset-[12%] border-2 border-white rounded-[18px] pointer-events-none shadow-[0_0_0_9999px_rgba(0,0,0,0.4)]"></div>
                <div class="absolute inset-[12%] pointer-events-none">
                    <div class="absolute left-1/3 top-0 bottom-0 border-l border-white/20"></div>
                    <div class="absolute left-2/3 top-0 bottom-0 border-l border-white/20"></div>
                    <div class="absolute top-1/3 left-0 right-0 border-t border-white/20"></div>
                    <div class="absolute top-2/3 left-0 right-0 border-t border-white/20"></div>
                </div>
                <div class="absolute bottom-3 left-0 right-0 text-center pointer-events-none">
                    <span class="inline-flex px-2.5 py-1 rounded-full bg-black/50 text-[8px] font-semibold text-white">Geser foto untuk mengatur posisi</span>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-[9px] font-semibold text-slate-500">Zoom</span>
                    <span id="zoomValue" class="text-[9px] font-bold text-[#123873]">100%</span>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" id="zoomOut" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14" />
                        </svg>
                    </button>
                    <input id="zoomRange" type="range" min="100" max="300" value="100" class="flex-1 accent-[#123873]">
                    <button type="button" id="zoomIn" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="px-4 pb-4 grid grid-cols-2 gap-2.5">
            <button type="button" id="cancelCrop" class="h-11 rounded-xl border border-slate-200 bg-white text-[10px] font-bold text-slate-600">Batal</button>
            <button type="button" id="applyCrop" class="h-11 rounded-xl bg-[#123873] text-white text-[10px] font-bold">Gunakan Foto</button>
        </div>
    </div>
</div>
<script>
    (function() {
        const fotoInput = document.getElementById('foto');
        const cropModal = document.getElementById('cropModal');
        const cropArea = document.getElementById('cropArea');
        const cropImage = document.getElementById('cropImage');
        const previewFoto = document.getElementById('previewFoto');
        const defaultFoto = document.getElementById('defaultFoto');
        const zoomRange = document.getElementById('zoomRange');
        const zoomValue = document.getElementById('zoomValue');
        const zoomIn = document.getElementById('zoomIn');
        const zoomOut = document.getElementById('zoomOut');
        const closeCrop = document.getElementById('closeCrop');
        const cancelCrop = document.getElementById('cancelCrop');
        const applyCrop = document.getElementById('applyCrop');
        let originalImage = new Image();
        let originalFile = null;
        let imageData = null;
        let baseScale = 1;
        let zoom = 1;
        let x = 0;
        let y = 0;
        let startX = 0;
        let startY = 0;
        let startImageX = 0;
        let startImageY = 0;
        let dragging = false;

        function showModal() {
            cropModal.classList.remove('hidden');
            cropModal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function hideModal() {
            cropModal.classList.add('hidden');
            cropModal.classList.remove('flex');
            document.body.style.overflow = '';
            dragging = false;
        }

        function updateImage() {
            const areaSize = cropArea.clientWidth;
            const scale = baseScale * zoom;
            const width = originalImage.naturalWidth * scale;
            const height = originalImage.naturalHeight * scale;
            const minX = areaSize - width;
            const minY = areaSize - height;
            if (x > 0) x = 0;
            if (y > 0) y = 0;
            if (x < minX) x = minX;
            if (y < minY) y = minY;
            cropImage.style.width = width + 'px';
            cropImage.style.height = height + 'px';
            cropImage.style.left = x + 'px';
            cropImage.style.top = y + 'px';
            cropImage.style.maxWidth = 'none';
            cropImage.style.maxHeight = 'none';
            zoomValue.textContent = Math.round(zoom * 100) + '%';
        }

        function prepareImage() {
            const areaSize = cropArea.clientWidth;
            if (!areaSize || !originalImage.naturalWidth || !originalImage.naturalHeight) {
                return;
            }
            const imageWidth = originalImage.naturalWidth;
            const imageHeight = originalImage.naturalHeight;
            baseScale = Math.max(areaSize / imageWidth, areaSize / imageHeight);
            zoom = 1;
            const width = imageWidth * baseScale;
            const height = imageHeight * baseScale;
            x = (areaSize - width) / 2;
            y = (areaSize - height) / 2;
            zoomRange.value = 100;
            cropImage.src = imageData;
            cropImage.style.display = 'block';
            updateImage();
        }
        fotoInput.addEventListener('change', function(event) {
            const file = event.target.files && event.target.files[0];
            if (!file) return;
            if (!file.type.startsWith('image/')) {
                alert('File yang dipilih bukan gambar.');
                fotoInput.value = '';
                return;
            }
            originalFile = file;
            const reader = new FileReader();
            reader.onload = function(e) {
                imageData = e.target.result;
                originalImage = new Image();
                originalImage.onload = function() {
                    showModal();
                    requestAnimationFrame(function() {
                        requestAnimationFrame(function() {
                            prepareImage();
                        });
                    });
                };
                originalImage.onerror = function() {
                    alert('Gambar tidak dapat dibaca.');
                    fotoInput.value = '';
                };
                originalImage.src = imageData;
            };
            reader.onerror = function() {
                alert('Gagal membaca file foto.');
                fotoInput.value = '';
            };
            reader.readAsDataURL(file);
        });
        zoomRange.addEventListener('input', function() {
            const newZoom = Number(this.value) / 100;
            const areaSize = cropArea.clientWidth;
            const centerX = areaSize / 2;
            const centerY = areaSize / 2;
            const oldScale = baseScale * zoom;
            const newScale = baseScale * newZoom;
            x = centerX - ((centerX - x) / oldScale) * newScale;
            y = centerY - ((centerY - y) / oldScale) * newScale;
            zoom = newZoom;
            updateImage();
        });
        zoomIn.addEventListener('click', function() {
            zoomRange.value = Math.min(300, Number(zoomRange.value) + 10);
            zoomRange.dispatchEvent(new Event('input'));
        });
        zoomOut.addEventListener('click', function() {
            zoomRange.value = Math.max(100, Number(zoomRange.value) - 10);
            zoomRange.dispatchEvent(new Event('input'));
        });

        function getPoint(event) {
            if (event.touches && event.touches.length) {
                return event.touches[0];
            }
            return event;
        }
        cropArea.addEventListener('mousedown', function(event) {
            event.preventDefault();
            const point = getPoint(event);
            dragging = true;
            startX = point.clientX;
            startY = point.clientY;
            startImageX = x;
            startImageY = y;
        });
        document.addEventListener('mousemove', function(event) {
            if (!dragging) return;
            event.preventDefault();
            x = startImageX + event.clientX - startX;
            y = startImageY + event.clientY - startY;
            updateImage();
        });
        document.addEventListener('mouseup', function() {
            dragging = false;
        });
        cropArea.addEventListener('touchstart', function(event) {
            event.preventDefault();
            const point = getPoint(event);
            dragging = true;
            startX = point.clientX;
            startY = point.clientY;
            startImageX = x;
            startImageY = y;
        }, {
            passive: false
        });
        document.addEventListener('touchmove', function(event) {
            if (!dragging) return;
            event.preventDefault();
            const point = getPoint(event);
            x = startImageX + point.clientX - startX;
            y = startImageY + point.clientY - startY;
            updateImage();
        }, {
            passive: false
        });
        document.addEventListener('touchend', function() {
            dragging = false;
        });

        function createCrop() {
            const canvas = document.createElement('canvas');
            const outputSize = 800;
            canvas.width = outputSize;
            canvas.height = outputSize;
            const ctx = canvas.getContext('2d');
            const areaSize = cropArea.clientWidth;
            const frameRatio = 0.76;
            const frameSize = areaSize * frameRatio;
            const frameLeft = (areaSize - frameSize) / 2;
            const frameTop = (areaSize - frameSize) / 2;
            const scale = baseScale * zoom;
            const sourceX = (frameLeft - x) / scale;
            const sourceY = (frameTop - y) / scale;
            const sourceSize = frameSize / scale;
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, outputSize, outputSize);
            ctx.imageSmoothingEnabled = true;
            ctx.imageSmoothingQuality = 'high';
            ctx.drawImage(
                originalImage,
                sourceX,
                sourceY,
                sourceSize,
                sourceSize,
                0,
                0,
                outputSize,
                outputSize
            );
            return canvas;
        }
        applyCrop.addEventListener('click', function() {
            if (!originalImage.naturalWidth) {
                alert('Foto belum siap diproses.');
                return;
            }
            applyCrop.disabled = true;
            applyCrop.textContent = 'Memproses...';
            const canvas = createCrop();
            canvas.toBlob(function(blob) {
                if (!blob) {
                    alert('Gagal memproses foto.');
                    applyCrop.disabled = false;
                    applyCrop.textContent = 'Gunakan Foto';
                    return;
                }
                const croppedFile = new File(
                    [blob],
                    'foto-profil.jpg', {
                        type: 'image/jpeg',
                        lastModified: Date.now()
                    }
                );
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(croppedFile);
                fotoInput.files = dataTransfer.files;
                const previewUrl = URL.createObjectURL(blob);
                previewFoto.src = previewUrl;
                previewFoto.classList.remove('hidden');
                if (defaultFoto) {
                    defaultFoto.classList.add('hidden');
                }
                hideModal();
                applyCrop.disabled = false;
                applyCrop.textContent = 'Gunakan Foto';
            }, 'image/jpeg', 0.90);
        });
        closeCrop.addEventListener('click', function() {
            fotoInput.value = '';
            hideModal();
        });
        cancelCrop.addEventListener('click', function() {
            fotoInput.value = '';
            hideModal();
        });
        cropModal.addEventListener('click', function(event) {
            if (event.target === cropModal) {
                fotoInput.value = '';
                hideModal();
            }
        });
    })();
</script>
@endsection