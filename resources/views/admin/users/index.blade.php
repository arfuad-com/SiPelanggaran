@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('page_title', 'Data Pengguna & Hak Akses')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Daftar Akun Pengguna</h3>
            <p class="text-xs text-slate-500">Kelola akun Admin, Guru BK, dan Guru Biasa dengan kredensial NIP.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-indigo-600/30 transition uppercase tracking-wider">
            <span>+</span> Tambah Pengguna Baru
        </a>
    </div>

    <!-- Filter & Search Card -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari nama atau NIP..." 
                    class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                >
            </div>
            <div>
                <select name="role" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <option value="">Semua Peran (Role)</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="bk" {{ request('role') === 'bk' ? 'selected' : '' }}>Guru BK</option>
                    <option value="guru" {{ request('role') === 'guru' ? 'selected' : '' }}>Guru Biasa</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-700 transition">
                    Filter
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Users List (Responsive: Cards for Mobile, Table for Desktop) -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 whitespace-nowrap">NIP</th>
                        <th class="py-3.5 px-4 min-w-[200px]">Nama Lengkap</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Peran (Role)</th>
                        <th class="py-3.5 px-4 min-w-[180px]">Kontak & Domisili</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Status</th>
                        <th class="py-3.5 px-4 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-800">{{ $user->nip }}</td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2.5">
                                    <img 
                                        src="{{ $user->avatar_url }}" 
                                        alt="{{ $user->name }}" 
                                        class="w-8 h-8 rounded-full object-cover ring-1 ring-slate-200 shadow-xs flex-shrink-0"
                                        onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0f172a&color=ffffff&size=128&bold=true'"
                                    >
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="font-bold text-slate-900 hover:text-indigo-600 hover:underline transition block leading-tight truncate">
                                            {{ $user->name }}
                                        </a>
                                        @if($user->position)
                                            <span class="text-[11px] text-slate-400 block mt-0.5 truncate">{{ $user->position }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center whitespace-nowrap px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider
                                    {{ $user->role === 'admin' ? 'bg-red-100 text-red-700 border border-red-200/60' : '' }}
                                    {{ $user->role === 'bk' ? 'bg-indigo-100 text-indigo-700 border border-indigo-200/60' : '' }}
                                    {{ $user->role === 'guru' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200/60' : '' }}">
                                    {{ $user->role === 'admin' ? 'Admin' : ($user->role === 'bk' ? 'Guru BK' : 'Guru') }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-500">
                                <div class="text-slate-700">{{ $user->email ?? '-' }}</div>
                                @if($user->phone)
                                    <div class="text-[11px] font-mono text-emerald-600 font-medium mt-0.5">📞 {{ $user->phone }}</div>
                                @endif
                                @if($user->domisili)
                                    <div class="text-[10px] text-slate-400 mt-0.5 truncate max-w-xs">📍 {{ $user->domisili }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-1.5 whitespace-nowrap">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white/80 hover:bg-slate-50 text-slate-600 hover:text-indigo-600 border border-slate-200 rounded-lg text-[11px] font-semibold transition shadow-2xs" title="Lihat Profil & Aktivitas">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Detail</span>
                                </a>

                                <form method="POST" action="{{ route('admin.users.reset-password', $user->id) }}" class="inline" onsubmit="return confirm('Reset kata sandi pengguna ini ke default (password123)?');">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white/80 hover:bg-slate-50 text-slate-600 hover:text-amber-600 border border-slate-200 rounded-lg text-[11px] font-semibold transition shadow-2xs" title="Reset Password">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                        <span>Reset</span>
                                    </button>
                                </form>

                                <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white/80 hover:bg-slate-50 text-slate-600 hover:text-slate-900 border border-slate-200 rounded-lg text-[11px] font-semibold transition shadow-2xs" title="Edit">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    <span>Edit</span>
                                </a>

                                @if($user->id !== Auth::id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus akun pengguna ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white/80 hover:bg-rose-50 text-slate-500 hover:text-rose-600 border border-slate-200 rounded-lg text-[11px] font-semibold transition shadow-2xs" title="Hapus">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Tidak ada pengguna ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card List View (No horizontal scrolling!) -->
        <div class="md:hidden divide-y divide-slate-100">
            @forelse($users as $user)
                <div class="p-4 space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <img 
                                src="{{ $user->avatar_url }}" 
                                alt="{{ $user->name }}" 
                                class="w-10 h-10 rounded-full object-cover ring-2 ring-slate-100 shadow-xs flex-shrink-0"
                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0f172a&color=ffffff&size=128&bold=true'"
                            >
                            <div class="min-w-0">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="font-bold text-slate-900 text-sm hover:text-indigo-600 block truncate">
                                    {{ $user->name }}
                                </a>
                                <p class="text-xs text-slate-500 font-mono">NIP: {{ $user->nip }}</p>
                                @if($user->position)
                                    <p class="text-[11px] text-slate-400 truncate mt-0.5">{{ $user->position }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col items-end gap-1 flex-shrink-0">
                            <span class="inline-flex items-center whitespace-nowrap px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                {{ $user->role === 'admin' ? 'bg-red-100 text-red-700 border border-red-200/60' : '' }}
                                {{ $user->role === 'bk' ? 'bg-indigo-100 text-indigo-700 border border-indigo-200/60' : '' }}
                                {{ $user->role === 'guru' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200/60' : '' }}">
                                {{ $user->role === 'admin' ? 'Admin' : ($user->role === 'bk' ? 'Guru BK' : 'Guru') }}
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-semibold {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    <!-- Detail Kontak & Domisili -->
                    <div class="bg-slate-50/80 rounded-xl p-2.5 text-xs space-y-1 border border-slate-100 text-slate-600">
                        @if($user->phone)
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">Telepon / WA:</span>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}" target="_blank" class="font-mono font-bold text-emerald-600 hover:underline">
                                    {{ $user->phone }}
                                </a>
                            </div>
                        @endif
                        @if($user->email)
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">Email:</span>
                                <span class="font-medium text-slate-700 truncate max-w-[200px]">{{ $user->email }}</span>
                            </div>
                        @endif
                        @if($user->domisili)
                            <div class="pt-1 border-t border-slate-200/60 flex items-start gap-1 text-[11px]">
                                <span class="text-slate-400 flex-shrink-0">Domisili:</span>
                                <span class="text-slate-700 font-medium">{{ $user->domisili }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Touch Actions Row (Subtle, Transparent, Clean SVGs) -->
                    <div class="grid grid-cols-3 gap-2 pt-1">
                        <a href="{{ route('admin.users.show', $user->id) }}" class="py-2 text-center bg-white/70 hover:bg-slate-50 active:scale-95 border border-slate-200/90 text-slate-600 hover:text-indigo-600 rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 shadow-2xs">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>Detail</span>
                        </a>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="py-2 text-center bg-white/70 hover:bg-slate-50 active:scale-95 border border-slate-200/90 text-slate-600 hover:text-slate-900 rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 shadow-2xs">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            <span>Edit</span>
                        </a>
                        <form method="POST" action="{{ route('admin.users.reset-password', $user->id) }}" onsubmit="return confirm('Reset kata sandi pengguna ini ke default (password123)?');">
                            @csrf
                            <button type="submit" class="w-full py-2 text-center bg-white/70 hover:bg-slate-50 active:scale-95 border border-slate-200/90 text-slate-600 hover:text-amber-700 rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 shadow-2xs">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                <span>Reset</span>
                            </button>
                        </form>
                    </div>

                    @if($user->id !== Auth::id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="block" onsubmit="return confirm('Yakin ingin menghapus akun pengguna ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full py-1.5 text-center bg-transparent hover:bg-rose-50/70 active:scale-95 text-slate-500 hover:text-rose-600 rounded-xl text-[11px] font-medium transition border border-slate-200/80 hover:border-rose-200 flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                <span>Hapus Akun</span>
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 text-xs">
                    Tidak ada pengguna ditemukan.
                </div>
            @endforelse
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
