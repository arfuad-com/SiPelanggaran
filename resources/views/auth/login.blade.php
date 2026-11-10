<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Masuk Sistem - SiPelanggaran</title>
    
    <!-- Google Fonts: Plus Jakarta Sans / Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', -apple-system, sans-serif; 
            -webkit-tap-highlight-color: transparent; 
        }
        .bg-mesh {
            background-color: #0b0f19;
            background-image: 
                radial-gradient(at 0% 0%, rgba(79, 70, 229, 0.18) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(99, 102, 241, 0.15) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(30, 41, 59, 0.4) 0px, transparent 100%);
        }
    </style>
</head>
<body class="bg-mesh text-slate-100 min-h-screen flex items-center justify-center p-4 sm:p-6 relative overflow-x-hidden selection:bg-indigo-500 selection:text-white">

    <!-- Decorative background glow spheres -->
    <div class="fixed top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[550px] h-[550px] bg-indigo-600/15 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="fixed bottom-10 right-1/4 w-[380px] h-[380px] bg-violet-600/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

    <div class="w-full max-w-md py-6 sm:py-8">
        
        <!-- Brand Header with modern badge -->
        <div class="text-center mb-6 sm:mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-600 to-indigo-400 text-white shadow-xl shadow-indigo-500/25 ring-4 ring-indigo-500/10 mb-4 transition-transform hover:scale-105 duration-200">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">SiPelanggaran</h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1.5 font-medium max-w-xs mx-auto">Sistem Informasi Monitoring Tata Tertib & Bimbingan Konseling</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xl shadow-indigo-950/40 border border-slate-100 text-slate-800 backdrop-blur-md">
            
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">Masuk ke Sistem</h2>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100/80">
                        Portal Staf & Guru
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Gunakan Nomor Induk Pegawai (NIP) dan kata sandi Anda.</p>
            </div>

            <!-- Flash Status Alert -->
            @if(session('status'))
                <div class="mb-5 p-3.5 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-800 text-xs font-medium flex items-start gap-2.5 shadow-sm">
                    <svg class="w-4 h-4 text-indigo-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Error Alerts -->
            @if($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs shadow-sm flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-rose-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div class="space-y-1">
                        @foreach($errors->all() as $err)
                            <p class="font-medium">• {{ $err }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- NIP Input -->
                <div>
                    <label for="nip" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nomor Induk Pegawai (NIP)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            name="nip" 
                            id="nip" 
                            value="{{ old('nip') }}" 
                            placeholder="Contoh: ADMIN001 atau NIP Guru" 
                            required 
                            autofocus
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 bg-slate-50/50 hover:bg-white focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-sm font-medium transition placeholder:text-slate-400"
                        >
                    </div>
                </div>

                <!-- Password Input with Show/Hide Eye Toggle -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            placeholder="••••••••" 
                            required 
                            class="w-full pl-10 pr-11 py-2.5 rounded-xl border border-slate-300 bg-slate-50/50 hover:bg-white focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-sm font-medium transition placeholder:text-slate-400"
                        >

                        <!-- Eye Toggle Button -->
                        <button 
                            type="button" 
                            id="togglePasswordBtn" 
                            onclick="togglePasswordVisibility()" 
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-700 focus:outline-none transition cursor-pointer"
                            aria-label="Tampilkan atau sembunyikan kata sandi"
                            title="Tampilkan / Sembunyikan Kata Sandi"
                        >
                            <!-- Eye Open Icon (Visible when password is hidden) -->
                            <svg id="eyeIcon" class="w-4 h-4 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <!-- Eye Slash Icon (Visible when password is shown) -->
                            <svg id="eyeSlashIcon" class="w-4 h-4 hidden transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2.5 text-slate-600 hover:text-slate-900 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer transition">
                        <span class="font-medium">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full py-3 px-4 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 active:scale-[0.99] text-white font-bold rounded-xl transition-all shadow-lg shadow-indigo-600/25 hover:shadow-indigo-600/35 text-xs uppercase tracking-wider flex items-center justify-center gap-2 group mt-2"
                >
                    <span>Masuk ke Akun</span>
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <!-- Quick Demo Credentials Box -->
            <div class="mt-7 pt-5 border-t border-slate-100">
                <div class="flex items-center justify-between mb-2.5">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        Akun Uji Coba Cepat:
                    </p>
                    <span id="demoFeedback" class="text-[11px] text-emerald-600 font-semibold opacity-0 transition-opacity flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        Terisi otomatis!
                    </span>
                </div>
                
                <div class="grid grid-cols-3 gap-2">
                    <button 
                        type="button" 
                        onclick="fillDemo('ADMIN001', 'password123')" 
                        class="group p-2.5 text-center rounded-xl bg-slate-50 hover:bg-indigo-50/60 border border-slate-200 hover:border-indigo-200 transition-all active:scale-95"
                    >
                        <span class="block text-xs font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">Admin</span>
                        <span class="text-[10px] text-slate-400 group-hover:text-indigo-500 block font-mono mt-0.5">ADMIN001</span>
                    </button>
                    <button 
                        type="button" 
                        onclick="fillDemo('198501012010011001', 'password123')" 
                        class="group p-2.5 text-center rounded-xl bg-slate-50 hover:bg-indigo-50/60 border border-slate-200 hover:border-indigo-200 transition-all active:scale-95"
                    >
                        <span class="block text-xs font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">Guru BK</span>
                        <span class="text-[10px] text-slate-400 group-hover:text-indigo-500 block font-mono mt-0.5">1985...</span>
                    </button>
                    <button 
                        type="button" 
                        onclick="fillDemo('199002022015021002', 'password123')" 
                        class="group p-2.5 text-center rounded-xl bg-slate-50 hover:bg-indigo-50/60 border border-slate-200 hover:border-indigo-200 transition-all active:scale-95"
                    >
                        <span class="block text-xs font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">Guru Mapel</span>
                        <span class="text-[10px] text-slate-400 group-hover:text-indigo-500 block font-mono mt-0.5">1990...</span>
                    </button>
                </div>
            </div>

        </div>
        
        <!-- Footer Info -->
        <p class="text-center text-xs text-slate-400/80 mt-5 font-normal">
            Pendaftaran publik ditutup. Akun dikelola oleh Administrator Sekolah.
        </p>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeSlashIcon = document.getElementById('eyeSlashIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        }

        function fillDemo(nip, pass) {
            const nipInput = document.getElementById('nip');
            const passwordInput = document.getElementById('password');
            const feedback = document.getElementById('demoFeedback');

            nipInput.value = nip;
            passwordInput.value = pass;

            // Optional subtle visual feedback that fields have been filled
            if (feedback) {
                feedback.classList.remove('opacity-0');
                feedback.classList.add('opacity-100');
                setTimeout(() => {
                    feedback.classList.remove('opacity-100');
                    feedback.classList.add('opacity-0');
                }, 1800);
            }
        }
    </script>
</body>
</html>
