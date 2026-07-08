@extends('layouts.admin-layout')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Dashboard Admin</h1>
        <p class="text-gray-500 text-sm mt-1">Selamat datang di sistem manajemen E-Tamu BKPSDM Kabupaten Tegal</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
            <div><p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Tamu</p><h3 class="text-4xl font-bold text-gray-900 mt-2">15</h3></div>
            <div class="w-14 h-14 rounded-2xl bg-blue-500 flex items-center justify-center text-white text-2xl shadow-lg shadow-blue-500/20"><i class="ri-group-line"></i></div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
            <div><p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Menunggu</p><h3 class="text-4xl font-bold text-gray-900 mt-2">0</h3></div>
            <div class="w-14 h-14 rounded-2xl bg-amber-500 flex items-center justify-center text-white text-2xl shadow-lg shadow-amber-500/20"><i class="ri-time-line"></i></div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
            <div><p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Selesai</p><h3 class="text-4xl font-bold text-gray-900 mt-2">15</h3></div>
            <div class="w-14 h-14 rounded-2xl bg-emerald-500 flex items-center justify-center text-white text-2xl shadow-lg shadow-emerald-500/20"><i class="ri-checkbox-circle-line"></i></div>
        </div>
    </div>
@endsection