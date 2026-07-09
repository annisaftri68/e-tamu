@extends('layouts.admin-layout')
@section('title', 'Statistik Kunjungan')
@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Statistik</h1>
    <p class="text-sm text-gray-500 mt-0.5">Statistik kunjungan tamu BKPSDM Kabupaten Tegal</p>
</div>

<!-- STATS BLOCKS (Gambar 4) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex justify-between items-center">
        <div><p class="text-xs font-semibold text-gray-400 uppercase">Total Kunjungan</p><h3 class="text-3xl font-bold text-gray-900 mt-1">{{ $totalTamu ?? 0 }}</h3></div>
        <div class="w-12 h-12 rounded-xl bg-blue-500 flex items-center justify-center text-white text-xl shadow-md shadow-blue-500/20"><i class="ri-group-line"></i></div>
    </div>
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex justify-between items-center">
        <div><p class="text-xs font-semibold text-gray-400 uppercase">Menunggu</p><h3 class="text-3xl font-bold text-gray-900 mt-1">{{ $menunggu ?? 0 }}</h3></div>
        <div class="w-12 h-12 rounded-xl bg-amber-500 flex items-center justify-center text-white text-xl shadow-md shadow-amber-500/20"><i class="ri-time-line"></i></div>
    </div>
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex justify-between items-center">
        <div><p class="text-xs font-semibold text-gray-400 uppercase">Selesai</p><h3 class="text-3xl font-bold text-gray-900 mt-1">{{ $selesai ?? 0 }}</h3></div>
        <div class="w-12 h-12 rounded-xl bg-emerald-500 flex items-center justify-center text-white text-xl shadow-md shadow-emerald-500/20"><i class="ri-checkbox-circle-line"></i></div>
    </div>
</div>

<!-- CHARTS CONTAINER BLOCK (Gambar 4 & 5) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm lg:col-span-2">
        <h4 class="font-bold text-gray-900 mb-4">Kunjungan per Bulan</h4>
        <div class="h-64"><canvas id="lineChart"></canvas></div>
    </div>
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <h4 class="font-bold text-gray-900 mb-4">Status Kunjungan</h4>
        <div class="h-64 flex justify-center"><canvas id="doughnutChart"></canvas></div>
    </div>
</div>

<div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm mb-8">
    <h4 class="font-bold text-gray-900 mb-4">Tujuan Bidang</h4>
    <div class="h-72"><canvas id="barChart"></canvas></div>
</div>

<!-- CHART SCRIPT ENGINE -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const statistikLabels = {!! json_encode($statistikLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']) !!};
        const statistikData = {!! json_encode($statistikData ?? [0, 0, 0, 0, 0, 0]) !!};
        const bidangLabels = {!! json_encode($bidangLabels ?? ['Sekretariat', 'MUTPRO', 'PSDM', 'PPEKA']) !!};
        const bidangData = {!! json_encode($bidangData ?? [0, 0, 0, 0]) !!};

        new Chart(document.getElementById('lineChart'), {
            type: 'line',
            data: {
                labels: statistikLabels,
                datasets: [{
                    label: 'Jumlah Tamu',
                    data: statistikData,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,0.1)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        new Chart(document.getElementById('doughnutChart'), {
            type: 'doughnut',
            data: {
                labels: ['Selesai', 'Menunggu'],
                datasets: [{ data: [{{ $selesai ?? 0 }}, {{ $menunggu ?? 0 }}], backgroundColor: ['#eab308', '#cbd5e1'] }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels: bidangLabels,
                datasets: [{ label: 'Jumlah', data: bidangData, backgroundColor: '#2563eb', borderRadius: 6 }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    });
</script>
@endsection