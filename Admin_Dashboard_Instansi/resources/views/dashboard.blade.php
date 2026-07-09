{{-- Menghubungkan ke layout induk utama Anda (sesuaikan jika nama filenya layouts.admin-layout) --}}
@extends('layouts.admin-layout')

@section('content')
<div class="space-y-6">
    
    <div class="border-b border-gray-200/60 pb-5">
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Dashboard Admin</h1>
        <p class="text-xs text-gray-400 mt-1">Selamat datang di sistem manajemen E-Tamu BKPSDM Kabupaten Tegal</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-6 rounded-2xl border border-gray-200/60 shadow-sm flex items-center justify-between transition-all hover:shadow-md">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Tamu</p>
                <p class="text-3xl font-black text-gray-900 mt-1.5">{{ $totalTamu ?? 15 }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="ri-group-line"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200/60 shadow-sm flex items-center justify-between transition-all hover:shadow-md">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Menunggu</p>
                <p class="text-3xl font-black text-amber-500 mt-1.5">{{ $tamuMenunggu ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl">
                <i class="ri-time-line"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200/60 shadow-sm flex items-center justify-between transition-all hover:shadow-md">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Selesai</p>
                <p class="text-3xl font-black text-emerald-600 mt-1.5">{{ $tamuSelesai ?? 15 }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="ri-checkbox-circle-line"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-200/60 shadow-sm flex flex-col">
            <div class="mb-4">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2">
                    <i class="ri-bar-chart-box-line text-blue-500"></i>
                    <span>Grafik Ikhtisar Kunjungan (7 Hari Terakhir)</span>
                </h2>
            </div>
            <div class="relative flex-1 min-h-[280px] w-full">
                <canvas id="grafikKunjunganUtama"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200/60 shadow-sm flex flex-col">
            <div class="mb-4">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2">
                    <i class="ri-history-line text-blue-500"></i>
                    <span>Aktivitas Tamu Terbaru</span>
                </h2>
            </div>
            
            <div class="flex-1 overflow-y-auto space-y-3 pr-1 max-h-[280px]">
                {{-- Menggunakan @forelse untuk membaca database tamu --}}
                @forelse($tamus ?? [] as $tamu)
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-between gap-2">
                        <div class="overflow-hidden">
                            <p class="text-xs font-bold text-gray-900 truncate capitalize">{{ $tamu->nama }}</p>
                            <p class="text-[10px] text-gray-400 truncate mt-0.5">{{ $tamu->instansi }} • {{ $tamu->keperluan }}</p>
                        </div>
                        <span class="text-[9px] font-mono bg-white px-2 py-0.5 rounded border border-gray-200 text-gray-400 flex-shrink-0">
                            {{ $tamu->created_at ? $tamu->created_at->format('H:i') : '00:00' }}
                        </span>
                    </div>
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-center p-6 my-auto">
                        <div class="w-12 h-12 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center text-xl mb-2">
                            <i class="ri-database-2-line"></i>
                        </div>
                        <p class="text-xs text-gray-400 italic">Belum ada riwayat data dari database</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('grafikKunjunganUtama').getContext('2d');
        
        // Membaca data array dari controller, jika kosong otomatis memakai dummy data agar grafik tidak hilang
        const labelsHari = {!! json_encode($grafikLabels ?? ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']) !!};
        const dataJumlah = {!! json_encode($grafikData ?? [5, 8, 12, 6, 15, 3, 9]) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelsHari,
                datasets: [{
                    label: 'Kunjungan',
                    data: dataJumlah,
                    borderColor: '#2563eb', // Blue-600
                    backgroundColor: 'rgba(37, 99, 235, 0.03)',
                    borderWidth: 3,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#2563eb',
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#f1f5f9' },
                        ticks: { color: '#94a3b8', font: { size: 10 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 10 } }
                    }
                }
            }
        });
    });
</script>
@endsection