<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>@yield('title', 'Dashboard') - SiPelanggaran</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Inter"', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    colors: {
                        slate: {
                            850: '#151e2e',
                            950: '#0b0f19',
                        },
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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; 
            -webkit-tap-highlight-color: transparent; 
        }

        /* Smooth Drawer */
        #mobile-drawer {
            transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1);
        }
        #mobile-drawer.open {
            transform: translateX(0) !important;
        }
        #mobile-backdrop {
            transition: opacity 0.25s ease, visibility 0.25s ease;
        }
        #mobile-backdrop.open {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col md:flex-row overflow-x-hidden selection:bg-slate-800 selection:text-white">

    <!-- ======================================================== -->
    <!-- 1. MOBILE TOP APP BAR (Sticky on Android & iOS)          -->
    <!-- ======================================================== -->
    <header class="md:hidden sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200/90 px-4 py-2.5 flex items-center justify-between shadow-xs">
        <div class="flex items-center gap-2.5">
            <!-- Hamburger Button -->
            <button 
                type="button" 
                id="hamburger-btn" 
                aria-label="Buka Menu"
                class="w-9 h-9 rounded-lg flex items-center justify-center text-slate-600 bg-slate-100 hover:bg-slate-200 active:scale-95 transition focus:outline-none"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6.5h16M4 12h16M4 17.5h16"></path>
                </svg>
            </button>

            <div>
                <h1 class="text-sm font-bold tracking-tight text-slate-900 leading-tight">SiPelanggaran</h1>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span id="mobile-live-clock" class="text-[10px] font-mono font-bold text-indigo-600">--:--:-- WIB</span>
                </div>
            </div>
        </div>

        <!-- Mobile User Profile Pill (Photo, Name & Role) -->
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 py-1 pl-2 pr-1 rounded-xl hover:bg-slate-100 active:scale-95 transition max-w-[48vw] min-w-0 group" title="Buka Profil Pengguna">
            <div class="text-right min-w-0">
                <p class="text-xs font-bold text-slate-800 truncate leading-tight group-hover:text-indigo-600 transition">{{ Auth::user()->name }}</p>
                <div class="flex items-center justify-end gap-1.5 mt-0.5">
                    <span class="inline-block text-[9px] font-bold uppercase px-1.5 py-0.2 rounded bg-slate-100 text-slate-600 border border-slate-200 leading-none whitespace-nowrap">
                        {{ Auth::user()->role === 'bk' ? 'Guru BK' : (Auth::user()->role === 'admin' ? 'Admin' : 'Guru') }}
                    </span>
                </div>
            </div>
            <img 
                src="{{ Auth::user()->avatar_url }}" 
                alt="{{ Auth::user()->name }}" 
                class="w-8 h-8 rounded-full object-cover ring-2 ring-slate-200 shadow-xs flex-shrink-0 group-hover:ring-indigo-500 transition"
                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0f172a&color=ffffff&size=128&bold=true'"
            >
        </a>
    </header>

    <!-- ======================================================== -->
    <!-- 2. MOBILE DRAWER OVERLAY & SIDE PANEL                     -->
    <!-- ======================================================== -->
    <div 
        id="mobile-backdrop" 
        class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-40 md:hidden opacity-0 invisible pointer-events-none transition-all"
    ></div>

    <div 
        id="mobile-drawer" 
        class="fixed inset-y-0 left-0 z-50 w-72 max-w-[82vw] bg-slate-900 text-slate-100 flex flex-col shadow-2xl -translate-x-full md:hidden border-r border-slate-800"
    >
        <!-- Drawer Header -->
        <div class="px-4 py-3.5 border-b border-slate-800 flex items-center justify-between">
            <div>
                <span class="text-sm font-bold tracking-tight text-white block">SiPelanggaran</span>
                <span class="text-[10px] text-slate-400 block">Sistem Bimbingan Konseling</span>
            </div>
            <button 
                type="button" 
                id="drawer-close-btn" 
                class="w-8 h-8 rounded-lg text-slate-400 hover:text-white bg-slate-800 flex items-center justify-center transition"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- User Info Drawer -->
        <a href="{{ route('profile.edit') }}" class="px-4 py-3.5 border-b border-slate-800/90 bg-slate-850 flex items-center gap-3 hover:bg-slate-800 transition group">
            <img 
                src="{{ Auth::user()->avatar_url }}" 
                alt="{{ Auth::user()->name }}" 
                class="w-10 h-10 rounded-full object-cover ring-2 ring-slate-700 flex-shrink-0 group-hover:ring-indigo-500 transition shadow-xs"
                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0f172a&color=ffffff&size=128&bold=true'"
            >
            <div class="min-w-0 flex-1">
                <p class="text-xs font-bold text-white truncate group-hover:text-indigo-300 transition">{{ Auth::user()->name }}</p>
                <div class="flex items-center gap-2 mt-0.5 text-[11px] text-slate-400">
                    <span class="font-mono text-[10px]">#{{ Auth::user()->nip }}</span>
                    <span>•</span>
                    <span class="uppercase font-bold text-[9px] px-1.5 py-0.2 rounded bg-slate-800 text-slate-300 border border-slate-700 whitespace-nowrap">
                        {{ Auth::user()->role === 'bk' ? 'Guru BK' : (Auth::user()->role === 'admin' ? 'Admin' : 'Guru') }}
                    </span>
                </div>
            </div>
        </a>

        <!-- Drawer Navigation Items -->
        <nav class="flex-1 px-3 py-3 space-y-1 text-xs overflow-y-auto">
            @include('layouts.partials.nav-links')
        </nav>

        <!-- Drawer Logout -->
        <div class="p-3 border-t border-slate-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-rose-300 hover:bg-rose-950/40 text-xs font-medium transition">
                    <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Keluar Sistem</span>
                </button>
            </form>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 3. DESKTOP STATIC SIDEBAR (Visible on Windows / PC)       -->
    <!-- ======================================================== -->
    <aside class="hidden md:flex w-64 bg-slate-900 text-slate-200 flex-col flex-shrink-0 min-h-screen border-r border-slate-800">
        <!-- Brand Header -->
        <div class="px-5 py-4 border-b border-slate-800">
            <h1 class="text-base font-bold tracking-tight text-white block">SiPelanggaran</h1>
            <p class="text-[11px] text-slate-400 block">Sistem Bimbingan & Konseling</p>
        </div>

        <!-- User Profile Card -->
        <a href="{{ route('profile.edit') }}" class="px-5 py-3.5 border-b border-slate-800/80 bg-slate-850 flex items-center gap-3 hover:bg-slate-800 transition group" title="Buka Profil Pengguna">
            <img 
                src="{{ Auth::user()->avatar_url }}" 
                alt="{{ Auth::user()->name }}" 
                class="w-10 h-10 rounded-full object-cover ring-2 ring-slate-700 flex-shrink-0 group-hover:ring-indigo-500 transition shadow-xs"
                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0f172a&color=ffffff&size=128&bold=true'"
            >
            <div class="min-w-0 flex-1">
                <p class="text-xs font-bold text-white truncate group-hover:text-indigo-300 transition">{{ Auth::user()->name }}</p>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="text-[10px] text-slate-400 font-mono">#{{ Auth::user()->nip }}</span>
                    <span class="text-[9px] font-bold uppercase px-1.5 py-0.2 rounded bg-slate-800 text-slate-300 border border-slate-700 whitespace-nowrap">
                        {{ Auth::user()->role === 'bk' ? 'Guru BK' : (Auth::user()->role === 'admin' ? 'Admin' : 'Guru') }}
                    </span>
                </div>
            </div>
        </a>

        <!-- Desktop Navigation Items -->
        <nav class="flex-1 px-3 py-3 space-y-1 text-xs overflow-y-auto">
            @include('layouts.partials.nav-links')
        </nav>

        <!-- Logout Action -->
        <div class="p-3 border-t border-slate-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition text-xs font-medium">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Keluar Sistem</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- ======================================================== -->
    <!-- 4. MAIN CONTENT AREA                                     -->
    <!-- ======================================================== -->
    <div class="flex-1 flex flex-col min-w-0 w-full min-h-screen">
        <!-- Desktop Header Bar -->
        <header class="hidden md:flex bg-white border-b border-slate-200 px-8 py-3 items-center justify-between sticky top-0 z-20 shadow-xs">
            <div>
                <h2 class="text-base font-bold text-slate-900 leading-tight">@yield('page_title', 'Aplikasi Monitoring BK')</h2>
                <p class="text-xs text-slate-500">Khusus Pencatatan Perilaku & Bimbingan Konseling Siswa</p>
            </div>
            
            <div class="flex items-center gap-3">
                <!-- Live Realtime Clock Widget -->
                <div class="hidden lg:flex items-center gap-2.5 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 shadow-2xs">
                    <div class="w-7 h-7 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-3.5 h-3.5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9" stroke-width="2" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7v5l3 2" />
                        </svg>
                    </div>
                    <div class="leading-tight">
                        <div class="flex items-center gap-1.5">
                            <span id="live-clock-time" class="font-mono font-extrabold text-slate-800 text-xs tracking-tight">--:--:-- WIB</span>
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                        </div>
                        <p id="live-clock-date" class="text-[10px] text-slate-500 font-medium">Memuat tanggal...</p>
                    </div>
                </div>

                <div class="hidden xl:flex items-center gap-2 text-xs text-slate-500 font-medium px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Tahun Ajaran Aktif</span>
                </div>

                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 pl-2 py-1 pr-1 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition group" title="Pengaturan Profil">
                    <div class="text-right">
                        <p class="text-xs font-bold text-slate-800 group-hover:text-indigo-600 transition">{{ Auth::user()->name }}</p>
                        <div class="flex items-center justify-end gap-1.5 mt-0.5">
                            <span class="text-[10px] text-slate-400 font-mono">NIP: {{ Auth::user()->nip }}</span>
                            <span class="text-[9px] font-bold uppercase px-1.5 py-0.2 rounded bg-slate-100 text-slate-600 border border-slate-200 whitespace-nowrap">
                                {{ Auth::user()->role === 'bk' ? 'Guru BK' : (Auth::user()->role === 'admin' ? 'Admin' : 'Guru') }}
                            </span>
                        </div>
                    </div>
                    <img 
                        src="{{ Auth::user()->avatar_url }}" 
                        alt="{{ Auth::user()->name }}" 
                        class="w-9 h-9 rounded-full object-cover ring-2 ring-slate-200 group-hover:ring-indigo-500 transition shadow-xs flex-shrink-0"
                        onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0f172a&color=ffffff&size=128&bold=true'"
                    >
                </a>
            </div>
        </header>

        <!-- Flash Messages & Main Body Content -->
        <main class="flex-1 p-3.5 sm:p-5 md:p-8 pb-24 md:pb-8 max-w-7xl w-full mx-auto">
            @if(session('success'))
                <div class="mb-4 p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-start gap-3 text-xs sm:text-sm">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <div>
                        <p class="font-bold text-emerald-900">Berhasil</p>
                        <p class="text-emerald-700 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-3.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-900 flex items-start gap-3 text-xs sm:text-sm">
                    <svg class="w-5 h-5 text-rose-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <div>
                        <p class="font-bold text-rose-900">Perhatian</p>
                        <p class="text-rose-700 mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-4 p-3.5 rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-900 flex items-start gap-3 text-xs sm:text-sm">
                    <svg class="w-5 h-5 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-bold text-indigo-900">Informasi</p>
                        <p class="text-indigo-700 mt-0.5">{{ session('info') }}</p>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Desktop Footer -->
        <footer class="hidden md:block bg-white border-t border-slate-200 px-8 py-3 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} SiPelanggaran • Sistem Informasi Monitoring Perilaku & Bimbingan Konseling Siswa.
        </footer>
    </div>

    <!-- ======================================================== -->
    <!-- 5. MOBILE BOTTOM NAVIGATION BAR (Thumb-friendly on Android)-->
    <!-- ======================================================== -->
    <nav class="md:hidden fixed bottom-0 inset-x-0 z-30 bg-white/95 backdrop-blur-md border-t border-slate-200 px-3 py-1.5 flex items-center justify-around shadow-lg">
        @if(Auth::user()->isBK())
            <a href="{{ route('bk.dashboard') }}" class="flex flex-col items-center py-1 px-2.5 rounded-lg text-[10px] font-semibold transition {{ request()->routeIs('bk.dashboard') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25l2.25-2.25 2.25 2.25 3-3"></path>
                </svg>
                <span>Analitik</span>
            </a>

            @php $pendingCountNav = \App\Models\StudentViolation::where('status', 'pending')->count(); @endphp
            <a href="{{ route('bk.validations.index') }}" class="relative flex flex-col items-center py-1 px-2.5 rounded-lg text-[10px] font-semibold transition {{ request()->routeIs('bk.validations.*') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Validasi</span>
                @if($pendingCountNav > 0)
                    <span class="absolute top-0 right-2 w-2 h-2 rounded-full bg-amber-500"></span>
                @endif
            </a>

            <a href="{{ route('bk.violations.create') }}" class="flex flex-col items-center py-1 px-2.5 rounded-lg text-[10px] font-semibold transition {{ request()->routeIs('bk.violations.create') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"></path>
                </svg>
                <span>Input</span>
            </a>

            <a href="{{ route('bk.students.index') }}" class="flex flex-col items-center py-1 px-2.5 rounded-lg text-[10px] font-semibold transition {{ request()->routeIs('bk.students.*') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                </svg>
                <span>Siswa</span>
            </a>

            <a href="{{ route('bk.counseling.index') }}" class="flex flex-col items-center py-1 px-2.5 rounded-lg text-[10px] font-semibold transition {{ request()->routeIs('bk.counseling.*') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.502 49.188 49.188 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"></path>
                </svg>
                <span>Konseling</span>
            </a>
        @endif

        @if(Auth::user()->isGuru())
            <a href="{{ route('guru.dashboard') }}" class="flex flex-col items-center py-1 px-2.5 rounded-lg text-[10px] font-semibold transition {{ request()->routeIs('guru.dashboard') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"></path>
                </svg>
                <span>Beranda</span>
            </a>

            <a href="{{ route('guru.violations.create') }}" class="flex flex-col items-center py-1 px-2.5 rounded-lg text-[10px] font-semibold transition {{ request()->routeIs('guru.violations.create') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Lapor</span>
            </a>

            <a href="{{ route('guru.violations.my-reports') }}" class="flex flex-col items-center py-1 px-2.5 rounded-lg text-[10px] font-semibold transition {{ request()->routeIs('guru.violations.my-reports') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-11.25-9h11.25"></path>
                </svg>
                <span>Riwayat</span>
            </a>

            <a href="{{ route('guru.students.index') }}" class="flex flex-col items-center py-1 px-2.5 rounded-lg text-[10px] font-semibold transition {{ request()->routeIs('guru.students.*') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                </svg>
                <span>Siswa</span>
            </a>

            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center py-1 px-2.5 rounded-lg text-[10px] font-semibold transition {{ request()->routeIs('profile.*') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"></path>
                </svg>
                <span>Profil</span>
            </a>
        @endif

        @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center py-1 px-3 rounded-lg text-[10px] font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"></path>
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center py-1 px-3 rounded-lg text-[10px] font-semibold transition {{ request()->routeIs('admin.users.*') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"></path>
                </svg>
                <span>Pengguna</span>
            </a>

            <a href="{{ route('admin.students.index') }}" class="flex flex-col items-center py-1 px-3 rounded-lg text-[10px] font-semibold transition {{ request()->routeIs('admin.students.*') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342"></path>
                </svg>
                <span>Siswa</span>
            </a>

            <a href="{{ route('admin.reports.index') }}" class="flex flex-col items-center py-1 px-3 rounded-lg text-[10px] font-semibold transition {{ request()->routeIs('admin.reports.*') ? 'text-indigo-600' : 'text-slate-500' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                </svg>
                <span>Laporan</span>
            </a>
        @endif
    </nav>



    <!-- Drawer JavaScript Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const hamburgerBtn = document.getElementById('hamburger-btn');
            const closeBtn = document.getElementById('drawer-close-btn');
            const drawer = document.getElementById('mobile-drawer');
            const backdrop = document.getElementById('mobile-backdrop');

            function openDrawer() {
                if (!drawer || !backdrop) return;
                drawer.classList.add('open');
                backdrop.classList.add('open');
                document.body.classList.add('overflow-hidden');
            }

            function closeDrawer() {
                if (!drawer || !backdrop) return;
                drawer.classList.remove('open');
                backdrop.classList.remove('open');
                document.body.classList.remove('overflow-hidden');
            }

            if (hamburgerBtn) hamburgerBtn.addEventListener('click', openDrawer);
            if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
            if (backdrop) backdrop.addEventListener('click', closeDrawer);
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeDrawer();
            });

            // Close when clicking nav items
            document.querySelectorAll('#mobile-drawer a').forEach(function(link) {
                link.addEventListener('click', closeDrawer);
            });

            // Live Realtime Digital Clock Widget
            function updateAppLiveClock() {
                const now = new Date();
                const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

                const day = dayNames[now.getDay()];
                const date = String(now.getDate()).padStart(2, '0');
                const month = monthNames[now.getMonth()];
                const year = now.getFullYear();

                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');

                const timeFormatted = `${hours}:${minutes}:${seconds} WIB`;
                const dateFormatted = `${day}, ${date} ${month} ${year}`;

                const elDesktopTime = document.getElementById('live-clock-time');
                const elDesktopDate = document.getElementById('live-clock-date');
                const elMobileTime = document.getElementById('mobile-live-clock');

                if (elDesktopTime) elDesktopTime.textContent = timeFormatted;
                if (elDesktopDate) elDesktopDate.textContent = dateFormatted;
                if (elMobileTime) elMobileTime.textContent = timeFormatted;
            }

            updateAppLiveClock();
            setInterval(updateAppLiveClock, 1000);
        });
    </script>

    @stack('scripts')
</body>
</html>
