@extends('layouts.app')

@section('title', 'Detail Akun: ' . $user->name)
@section('page_title', 'Profil & Aktivitas Pengguna')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Actions -->
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-2 text-xs">
            <a href="{{ route('admin.users.index') }}" class="font-semibold text-slate-500 hover:text-slate-800 transition flex items-center gap-1">
                <span>&larr;</span> Manajemen Pengguna
            </a>
            <span class="text-slate-300">/</span>
            <a href="{{ route('admin.reports.index') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 transition">
                Laporan Global
            </a>
            <span class="text-slate-300">/</span>
            <span class="text-slate-700 font-bold truncate">{{ $user->name }}</span>
        </div>

            <!-- Reset Password -->
            <form method="POST" action="{{ route('admin.users.reset-password', $user->id) }}" class="inline" onsubmit="return confirm('Reset kata sandi pengguna ini ke default (password123)?');">
                @csrf
                <button type="submit" class="px-3 py-2 bg-white/80 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-semibold transition flex items-center gap-1.5 border border-slate-200 shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    <span>Reset Sandi</span>
                </button>
            </form>

            <a href="{{ route('admin.users.edit', $user->id) }}" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition shadow-xs flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <span>Edit Akun</span>
            </a>
        </div>

    <!-- User Profile Header Card -->
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <img 
                src="{{ $user->avatar_url }}" 
                alt="{{ $user->name }}" 
                class="w-16 h-16 rounded-2xl object-cover ring-2 ring-slate-100 shadow-md flex-shrink-0"
                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0f172a&color=ffffff&size=128&bold=true'"
            >
            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900">{{ $user->name }}</h2>
                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold uppercase tracking-wider
                        {{ $user->role === 'admin' ? 'bg-red-100 text-red-700 border border-red-200' : '' }}
                        {{ $user->role === 'bk' ? 'bg-indigo-100 text-indigo-700 border border-indigo-200' : '' }}
                        {{ $user->role === 'guru' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : '' }}">
                        {{ $user->role === 'bk' ? 'Guru BK' : ($user->role === 'admin' ? 'Super Admin' : 'Guru / Wali Kelas') }}
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                        {{ $user->is_active ? '● Akun Aktif' : '○ Nonaktif' }}
                    </span>
                </div>

                <div class="flex items-center gap-4 mt-2 text-xs text-slate-500 font-medium flex-wrap">
                    <span>NIP: <strong class="font-mono text-slate-800">{{ $user->nip }}</strong></span>
                    <span>•</span>
                    <span>Email: <strong class="text-slate-800">{{ $user->email ?? '-' }}</strong></span>
                    <span>•</span>
                    <span>Terdaftar: <span class="text-slate-600">{{ $user->created_at->format('d M Y') }}</span></span>
                </div>
            </div>
        </div>

        <!-- Metric Badges -->
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 sm:gap-3 w-full md:w-auto">
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-center">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Dilaporkan</span>
                <span class="text-xl font-black text-slate-800 block mt-0.5">{{ $stats['total_reported'] }}</span>
                <span class="text-[10px] text-slate-500">Laporan Kasus</span>
            </div>

            @if($user->role === 'bk')
                <div class="bg-indigo-50/60 p-3 rounded-xl border border-indigo-100 text-center">
                    <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider block">Validasi BK</span>
                    <span class="text-xl font-black text-indigo-700 block mt-0.5">{{ $stats['total_validated'] }}</span>
                    <span class="text-[10px] text-indigo-600">Disetujui</span>
                </div>

                <div class="bg-emerald-50/60 p-3 rounded-xl border border-emerald-100 text-center">
                    <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-wider block">Konseling</span>
                    <span class="text-xl font-black text-emerald-700 block mt-0.5">{{ $stats['total_counseling'] }}</span>
                    <span class="text-[10px] text-emerald-600">Sesi Siswa</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Informasi Biodata & Domisili Pegawai -->
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
        <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
            <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold text-sm">📋</span>
            <div>
                <h3 class="text-sm font-bold text-slate-900">Biodata Lengkap & Alamat Domisili</h3>
                <p class="text-xs text-slate-400">Informasi identitas personal dan domisili tempat tinggal pegawai</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs">
            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                <span class="text-[11px] font-semibold text-slate-400 uppercase block">Jabatan / Tugas</span>
                <span class="text-slate-800 font-bold mt-1 block text-sm">{{ $user->position ?? 'Tenaga Pendidik / Pegawai' }}</span>
            </div>

            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                <span class="text-[11px] font-semibold text-slate-400 uppercase block">Jenis Kelamin</span>
                <span class="text-slate-800 font-bold mt-1 block text-sm">
                    @if($user->gender === 'L')
                        👨 Laki-laki (L)
                    @elseif($user->gender === 'P')
                        👩 Perempuan (P)
                    @else
                        -
                    @endif
                </span>
            </div>

            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                <span class="text-[11px] font-semibold text-slate-400 uppercase block">Kontak HP / WhatsApp</span>
                <span class="mt-1 block text-sm">
                    @if($user->phone)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}" target="_blank" class="font-mono font-bold text-emerald-600 hover:underline flex items-center gap-1">
                            <span>💬</span> {{ $user->phone }}
                        </a>
                    @else
                        <span class="text-slate-400 font-mono">-</span>
                    @endif
                </span>
            </div>

            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100 sm:col-span-2 md:col-span-1">
                <span class="text-[11px] font-semibold text-slate-400 uppercase block">Email Akun</span>
                <span class="text-slate-800 font-medium mt-1 block text-sm truncate">{{ $user->email ?? '-' }}</span>
            </div>
        </div>

        <div class="mt-3.5 p-3.5 bg-slate-50 rounded-xl border border-slate-100 text-xs">
            <div class="flex items-start gap-2">
                <span class="text-slate-400 mt-0.5">📍</span>
                <div>
                    <span class="text-[11px] font-bold text-slate-500 uppercase block">Alamat Domisili Tempat Tinggal</span>
                    <p class="text-slate-800 mt-0.5 font-medium leading-relaxed">{{ $user->domisili ?? 'Belum ada data alamat domisili yang tersimpan.' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Pelanggaran yang Dilaporkan Akun Ini -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Riwayat Laporan Pelanggaran oleh {{ $user->name }}</h3>
                <p class="text-xs text-slate-400">Seluruh pencatatan pelanggaran yang diajukan oleh pengguna ini.</p>
            </div>
            <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full font-bold text-xs">
                Total: {{ $user->reportedViolations->count() }} Data
            </span>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Siswa</th>
                        <th class="py-3 px-4">Kelas</th>
                        <th class="py-3 px-4">Pelanggaran</th>
                        <th class="py-3 px-4 text-center">Poin</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Validator BK</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($user->reportedViolations as $v)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 text-slate-500 whitespace-nowrap">{{ $v->violation_date->format('d/m/Y') }}</td>
                            <td class="py-3 px-4 font-bold text-slate-800">
                                <a href="{{ route('admin.students.show', $v->student->id) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline">
                                    {{ $v->student->name }}
                                </a>
                            </td>
                            <td class="py-3 px-4 text-slate-600">{{ $v->student->schoolClass->name }}</td>
                            <td class="py-3 px-4">
                                <span class="font-semibold text-slate-800 block">{{ $v->category->name }}</span>
                                @if($v->notes)
                                    <span class="text-[11px] text-slate-400 italic mt-0.5 line-clamp-1">"{{ $v->notes }}"</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-0.5 rounded font-black text-rose-600 bg-rose-50 border border-rose-100">
                                    +{{ $v->category->points }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                    {{ $v->status === 'validated' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                    {{ $v->status === 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $v->status === 'rejected' ? 'bg-rose-100 text-rose-800' : '' }}">
                                    {{ $v->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                {{ $v->validator->name ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">Pengguna ini belum pernah mencatat/melaporkan pelanggaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card List View -->
        <div class="sm:hidden divide-y divide-slate-100">
            @forelse($user->reportedViolations as $v)
                <div class="p-4 space-y-2">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <a href="{{ route('admin.students.show', $v->student->id) }}" class="font-bold text-slate-900 text-xs hover:text-indigo-600 block truncate">
                                {{ $v->student->name }}
                            </a>
                            <span class="text-[11px] text-slate-500">{{ $v->student->schoolClass->name }}</span>
                        </div>
                        <div class="flex flex-col items-end gap-1 flex-shrink-0">
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase
                                {{ $v->status === 'validated' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                {{ $v->status === 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                                {{ $v->status === 'rejected' ? 'bg-rose-100 text-rose-800' : '' }}">
                                {{ $v->status }}
                            </span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $v->violation_date->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100 text-xs">
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-semibold text-slate-800 truncate">{{ $v->category->name }}</span>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold text-rose-600 bg-rose-50 border border-rose-100 flex-shrink-0">
                                +{{ $v->category->points }} Poin
                            </span>
                        </div>
                        @if($v->notes)
                            <p class="text-[11px] text-slate-500 italic mt-1">"{{ $v->notes }}"</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-slate-400 text-xs">Pengguna ini belum pernah mencatat/melaporkan pelanggaran.</div>
            @endforelse
        </div>
    </div>

    <!-- Jika Guru BK: Tampilkan Riwayat Sesi Konseling -->
    @if($user->role === 'bk' && $user->counselingSessions->count() > 0)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Riwayat Sesi Bimbingan Konseling oleh {{ $user->name }}</h3>
                    <p class="text-xs text-slate-400">Catatan pendampingan konseling yang dipimpin konselor ini.</p>
                </div>
                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full font-bold text-xs">
                    Total: {{ $user->counselingSessions->count() }} Sesi
                </span>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Siswa</th>
                            <th class="py-3 px-4">Kelas</th>
                            <th class="py-3 px-4">Permasalahan</th>
                            <th class="py-3 px-4">Hasil Pembinaan</th>
                            <th class="py-3 px-4">Rencana Tindak Lanjut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($user->counselingSessions as $c)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3 px-4 text-slate-500 whitespace-nowrap">{{ $c->counseling_date->format('d/m/Y') }}</td>
                                <td class="py-3 px-4 font-bold text-slate-800">
                                    <a href="{{ route('admin.students.show', $c->student->id) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline">
                                        {{ $c->student->name }}
                                    </a>
                                </td>
                                <td class="py-3 px-4 text-slate-600">{{ $c->student->schoolClass->name }}</td>
                                <td class="py-3 px-4 max-w-xs text-slate-700">{{ $c->issue_summary }}</td>
                                <td class="py-3 px-4 max-w-xs text-slate-600">{{ $c->counseling_result }}</td>
                                <td class="py-3 px-4 max-w-xs text-slate-600">{{ $c->follow_up_action ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List View -->
            <div class="sm:hidden divide-y divide-slate-100">
                @foreach($user->counselingSessions as $c)
                    <div class="p-4 space-y-2">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <a href="{{ route('admin.students.show', $c->student->id) }}" class="font-bold text-slate-900 text-xs hover:text-indigo-600 block truncate">
                                    {{ $c->student->name }}
                                </a>
                                <span class="text-[11px] text-slate-500">{{ $c->student->schoolClass->name }}</span>
                            </div>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $c->counseling_date->format('d/m/Y') }}</span>
                        </div>

                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100 text-xs space-y-1">
                            <p class="text-slate-800 font-medium">{{ $c->issue_summary }}</p>
                            <p class="text-slate-600 text-[11px]">{{ $c->counseling_result }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
