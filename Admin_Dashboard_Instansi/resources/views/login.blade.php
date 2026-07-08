<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - F-Tamu BKPSDM Kab. Tegal</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
</head>
<body class="min-h-screen bg-cover bg-center bg-no-repeat flex items-center justify-center p-4 relative" 
      style="background-image: linear-gradient(rgba(15, 23, 42, 0.65), rgba(15, 23, 42, 0.8)), url('{{ asset('images/bg-instansi.jpeg') }}');">

    <div class="bg-white w-full max-w-md rounded-3xl p-8 shadow-2xl border border-white/20 backdrop-blur-sm relative z-10 transition-all duration-300">
        
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-50/50 rounded-2xl mb-4 p-2 border border-blue-100/50 shadow-inner">
                <img src="{{ asset('images/logo-dinas-bkpsdm.png') }}" alt="Logo BKPSDM" class="w-full h-full object-contain">
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Selamat Datang Admin</h1>
            <p class="text-xs text-gray-400 mt-1">E-Tamu Digital BKPSDM Kabupaten Tegal</p>
        </div>

        @if($errors->has('loginError'))
        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-xl flex items-start gap-3 shadow-sm animate-shake">
            <i class="ri-error-warning-line text-red-500 text-lg mt-0.5"></i>
            <p class="text-xs font-semibold text-red-700 leading-relaxed">{{ $errors->first('loginError') }}</p>
        </div>
        @endif

        <form action="{{ route('login.proses') }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Username / NIP</label>
                <div class="relative flex items-center">
                    <i class="ri-user-line absolute left-4 text-gray-400 text-lg"></i>
                    <input type="text" name="username" value="{{ old('username') }}" required 
                           class="w-full pl-12 pr-4 py-3.5 bg-gray-50/70 border border-gray-200 rounded-xl text-sm outline-none focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all text-gray-700" 
                           placeholder="Masukkan Username atau NIP">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                <div class="relative flex items-center">
                    <i class="ri-lock-line absolute left-4 text-gray-400 text-lg"></i>
                    <input type="password" id="password" name="password" required 
                           class="w-full pl-12 pr-12 py-3.5 bg-gray-50/70 border border-gray-200 rounded-xl text-sm outline-none focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all text-gray-700" 
                           placeholder="••••••••">
                    <button type="button" onclick="togglePasswordVisibility()" class="absolute right-4 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                        <i id="toggle-icon" class="ri-eye-line text-lg"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white font-bold rounded-xl text-sm shadow-lg shadow-blue-600/20 transition-all flex items-center justify-center gap-2 mt-2 cursor-pointer">
                <span>Masuk ke Dashboard</span>
                <i class="ri-arrow-right-line"></i>
            </button>
        </form>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggle-icon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.className = 'ri-eye-off-line text-lg';
            } else {
                passwordField.type = 'password';
                toggleIcon.className = 'ri-eye-line text-lg';
            }
        }
    </script>
</body>
</html>