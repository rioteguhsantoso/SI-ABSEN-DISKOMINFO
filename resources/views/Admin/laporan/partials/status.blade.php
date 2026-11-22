@php
$status = $status ?? 'menunggu';

$warna = match($status) {
'disetujui' => 'bg-emerald-50 text-emerald-700',
'ditolak' => 'bg-red-50 text-red-700',
default => 'bg-amber-50 text-amber-700',
};
@endphp

<span class="rounded-lg px-2.5 py-1.5 text-[10px] font-bold {{ $warna }}">
    {{ ucfirst($status) }}
</span>