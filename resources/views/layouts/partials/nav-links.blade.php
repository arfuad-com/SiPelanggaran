{{-- ======================================================== --}}
{{-- 1. ROLE: ADMIN (Super User)                             --}}
{{-- ======================================================== --}}
@if(Auth::user()->isAdmin())
    <div class="px-2 py-1 text-[10px] font-semibold uppercase tracking-wider text-slate-500">
        Super Admin
    </div>

    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
        <svg class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"></path>
        </svg>
        <span>Dashboard Global</span>
    </a>

    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
        <svg class="w-4 h-4 {{ request()->routeIs('admin.users.*') ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"></path>
        </svg>
        <span>Manajemen Pengguna</span>
    </a>

    <div class="pt-2 pb-1">
        <div class="px-2 py-1 text-[10px] font-semibold uppercase tracking-wider text-slate-500">
            Master Data
        </div>
        <a href="{{ route('admin.classes.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('admin.classes.*') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
            <svg class="w-4 h-4 {{ request()->routeIs('admin.classes.*') ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"></path>
            </svg>
            <span>Kelas & Jurusan</span>
        </a>
        <a href="{{ route('admin.students.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('admin.students.*') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
            <svg class="w-4 h-4 {{ request()->routeIs('admin.students.*') ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342"></path>
            </svg>
            <span>Data Siswa</span>
        </a>
        <a href="{{ route('admin.violation-categories.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('admin.violation-categories.*') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
            <svg class="w-4 h-4 {{ request()->routeIs('admin.violation-categories.*') ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path>
            </svg>
            <span>Kategori Pelanggaran</span>
        </a>
    </div>

    <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('admin.reports.*') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
        <svg class="w-4 h-4 {{ request()->routeIs('admin.reports.*') ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
        </svg>
        <span>Laporan Global</span>
    </a>
@endif

{{-- ======================================================== --}}
{{-- 2. ROLE: GURU BK (Konselor & Validator)                 --}}
{{-- ======================================================== --}}
@if(Auth::user()->isBK())
    <div class="px-2 py-1 text-[10px] font-semibold uppercase tracking-wider text-slate-500">
        Konselor BK
    </div>

    <a href="{{ route('bk.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('bk.dashboard') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
        <svg class="w-4 h-4 {{ request()->routeIs('bk.dashboard') ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25l2.25-2.25 2.25 2.25 3-3"></path>
        </svg>
        <span>Pusat Analitik</span>
    </a>

    @php $pendingNavCount = \App\Models\StudentViolation::where('status', 'pending')->count(); @endphp
    <a href="{{ route('bk.validations.index') }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('bk.validations.*') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
        <div class="flex items-center gap-3">
            <svg class="w-4 h-4 {{ request()->routeIs('bk.validations.*') ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Validasi Pelanggaran</span>
        </div>
        @if($pendingNavCount > 0)
            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-500 text-slate-950">
                {{ $pendingNavCount }}
            </span>
        @endif
    </a>

    <a href="{{ route('bk.violations.create') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('bk.violations.*') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
        <svg class="w-4 h-4 {{ request()->routeIs('bk.violations.*') ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"></path>
        </svg>
        <span>Input Kasus (Langsung)</span>
    </a>

    <a href="{{ route('bk.students.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('bk.students.*') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
        <svg class="w-4 h-4 {{ request()->routeIs('bk.students.*') ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
        </svg>
        <span>Rekam Jejak Siswa</span>
    </a>

    <a href="{{ route('bk.counseling.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('bk.counseling.*') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
        <svg class="w-4 h-4 {{ request()->routeIs('bk.counseling.*') ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.502 49.188 49.188 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"></path>
        </svg>
        <span>Bimbingan Konseling</span>
    </a>
@endif

{{-- ======================================================== --}}
{{-- 3. ROLE: GURU BIASA (Wali Kelas / Guru Mapel)           --}}
{{-- ======================================================== --}}
@if(Auth::user()->isGuru())
    <div class="px-2 py-1 text-[10px] font-semibold uppercase tracking-wider text-slate-500">
        Guru & Wali Kelas
    </div>

    <a href="{{ route('guru.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('guru.dashboard') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
        <svg class="w-4 h-4 {{ request()->routeIs('guru.dashboard') ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"></path>
        </svg>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('guru.violations.create') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('guru.violations.create') ? 'bg-indigo-600 text-white font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
        <svg class="w-4 h-4 {{ request()->routeIs('guru.violations.create') ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>Lapor Pelanggaran</span>
    </a>

    <a href="{{ route('guru.violations.my-reports') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('guru.violations.my-reports') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
        <svg class="w-4 h-4 {{ request()->routeIs('guru.violations.my-reports') ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-11.25-9h11.25"></path>
        </svg>
        <span>Riwayat Laporan Saya</span>
    </a>

    <a href="{{ route('guru.students.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('guru.students.*') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
        <svg class="w-4 h-4 {{ request()->routeIs('guru.students.*') ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
        </svg>
        <span>Data Siswa (Histori)</span>
    </a>
@endif

{{-- Profil & Keamanan --}}
<div class="pt-3 border-t border-slate-800/80">
    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition-colors {{ request()->routeIs('profile.*') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
        <svg class="w-4 h-4 {{ request()->routeIs('profile.*') ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"></path>
        </svg>
        <span>Profil & Kata Sandi</span>
    </a>
</div>
