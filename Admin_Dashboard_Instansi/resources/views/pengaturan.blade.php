@extends('welcome') {{-- Menggunakan layout admin utama Anda --}}

@section('content')
<div class="space-y-6">
    
    <div class="border-b border-gray-100 pb-4">
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2">
            <i class="ri-settings-4-line text-blue-600"></i>
            <span>Pengaturan Manajemen Akun</span>
        </h1>
        <p class="text-xs text-gray-400 mt-1">Kelola dan daftarkan akun admin resmi E-Tamu BKPSDM Kabupaten Tegal</p>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-50 border-l-4 border-green-500 rounded-xl flex items-center gap-3 shadow-sm transition-all">
        <i class="ri-checkbox-circle-line text-green-500 text-lg"></i>
        <p class="text-xs font-semibold text-green-700">{{ session('success') }}</p>
    </div>
    @endif

    @if($errors->any())
    <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-xl flex items-center gap-3 shadow-sm transition-all">
        <i class="ri-error-warning-line text-red-500 text-lg"></i>
        <p class="text-xs font-semibold text-red-700">{{ $errors->first() }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        @if(Auth::user()->username === 'adminbkpsdm')
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="ri-user-add-line text-blue-500"></i>
                    <span>Tambah Admin Baru</span>
                </h2>
                
                <form action="{{ route('pengaturan.tambah_admin') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none bg-gray-50/50 focus:border-blue-500 focus:bg-white transition-all text-gray-700" placeholder="Contoh: Ahmad Fauzi">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">NIP Pegawai</label>
                        <input type="text" name="nip" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none bg-gray-50/50 focus:border-blue-500 focus:bg-white transition-all text-gray-700" placeholder="Masukkan 18 digit NIP">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Username Login</label>
                        <input type="text" name="username" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none bg-gray-50/50 focus:border-blue-500 focus:bg-white transition-all text-gray-700" placeholder="Contoh: adminfauzi">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                        <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm outline-none bg-gray-50/50 focus:border-blue-500 focus:bg-white transition-all text-gray-700" placeholder="Minimal 6 karakter">
                    </div>
                    
                    <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white font-bold rounded-xl text-sm shadow-md shadow-blue-600/10 transition-all flex items-center justify-center gap-2 mt-2">
                        <i class="ri-save-3-line"></i>
                        <span>Simpan Admin Baru</span>
                    </button>
                </form>
            </div>
        @endif

        <div class="{{ Auth::user()->username === 'adminbkpsdm' ? 'lg:col-span-2' : 'lg:col-span-3' }} bg-white p-6 rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                <i class="ri-team-line text-blue-500"></i>
                <span>Daftar Akun Admin Terdaftar</span>
            </h2>

            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/70 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="p-4">Nama / NIP</th>
                            <th class="p-4">Username</th>
                            <th class="p-4 text-center">Aksi Manajemen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm text-gray-700">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50/30 transition-colors">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-blue-50 text-blue-600 font-bold rounded-xl flex items-center justify-center text-sm uppercase">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-950">{{ $user->name }}</p>
                                        <p class="text-[11px] text-gray-400 font-mono mt-0.5">NIP: {{ $user->nip }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="bg-gray-100 px-2.5 py-1 rounded-md text-xs font-mono text-gray-600 border border-gray-200/50">{{ $user->username }}</span>
                            </td>
                            <td class="p-4 text-center">
                                @if($user->id !== Auth::id())
                                    @if(Auth::user()->username === 'adminbkpsdm')
                                        <form action="{{ url('/pengaturan/hapus-admin/'.$user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus admin ini dari sistem?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-500 hover:bg-red-50 hover:text-red-600 rounded-xl transition-all inline-flex items-center justify-center" title="Hapus Akun">
                                                <i class="ri-delete-bin-6-line text-lg"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400 font-mono italic">- No Access -</span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-600 text-xs font-semibold rounded-full border border-blue-100 animate-pulse">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                        <span>Anda Sedang Aktif</span>
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div> {{-- Penutup grid induk utama --}}
</div>
@endsection