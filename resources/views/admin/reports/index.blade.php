@extends('layouts.app')

@section('title', 'Laporan Global Sekolah')
@section('page_title', 'Rekapitulasi Global Pelanggaran & Konseling')

@section('content')
<div class="space-y-8">
    <!-- Filter Card -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Filter Periode & Kelas</h3>
        <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div>
                <label class="block text-[11px] font-bold text-slate-400 mb-1">DARI TANGGAL</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-400 mb-1">SAMPAI TANGGAL</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-400 mb-1">FILTER KELAS</label>
                <select name="class_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-bold text-xs rounded-xl transition shadow-xs">
                    Terapkan Filter
                </button>
                <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 bg-slate-100 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-200 active:scale-95 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- SEKSI 1: Rekapitulasi Pelanggaran Ter-validasi -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">1. Riwayat Pelanggaran Siswa (Validated)</h3>
                <p class="text-xs text-slate-400">Seluruh pelanggaran yang telah disetujui Guru BK. Klik nama siswa atau akun pelapor untuk melihat detail.</p>
            </div>
            <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full font-bold text-xs">
                Total: {{ $violations->total() }} Data
            </span>
        </div>

        <!-- SEKSI 1: Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Siswa</th>
                        <th class="py-3 px-4">Kelas</th>
                        <th class="py-3 px-4">Jenis Pelanggaran</th>
                        <th class="py-3 px-4 text-center">Poin</th>
                        <th class="py-3 px-4">Pelapor</th>
                        <th class="py-3 px-4">Validator BK</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($violations as $v)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap">{{ $v->violation_date->format('d/m/Y') }}</td>
                            
                            <!-- Siswa (Klik untuk detail / modal) -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-1.5 group">
                                    <a href="{{ route('admin.students.show', $v->student->id) }}" 
                                       class="font-bold text-slate-900 group-hover:text-indigo-600 group-hover:underline transition inline-flex items-center gap-1"
                                       title="Klik untuk membuka rekam jejak lengkap {{ $v->student->name }}">
                                        <span>{{ $v->student->name }}</span>
                                    </a>
                                    <button 
                                        type="button" 
                                        onclick="openStudentQuickModal({{ json_encode([
                                            'id' => $v->student->id,
                                            'name' => $v->student->name,
                                            'nisn' => $v->student->nisn,
                                            'nis' => $v->student->nis ?? '-',
                                            'class_name' => $v->student->schoolClass->name ?? '-',
                                            'gender' => $v->student->gender === 'L' ? 'Laki-laki (L)' : 'Perempuan (P)',
                                            'points' => $v->student->total_violation_points,
                                            'parent_name' => $v->student->parent_name ?? '-',
                                            'parent_phone' => $v->student->parent_phone ?? '-',
                                            'show_url' => route('admin.students.show', $v->student->id),
                                            'edit_url' => route('admin.students.edit', $v->student->id),
                                        ]) }})"
                                        class="p-1 rounded-md text-slate-300 hover:text-indigo-600 hover:bg-indigo-50 transition" 
                                        title="Pratinjau cepat">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                                <span class="text-[10px] text-slate-400 font-mono block">NISN: {{ $v->student->nisn }}</span>
                            </td>

                            <!-- Kelas -->
                            <td class="py-3.5 px-4 text-slate-600 whitespace-nowrap">
                                <span class="px-2 py-0.5 bg-slate-100 rounded-md font-medium text-slate-700">
                                    {{ $v->student->schoolClass->name }}
                                </span>
                            </td>

                            <!-- Jenis Pelanggaran -->
                            <td class="py-3.5 px-4 max-w-xs">
                                <p class="font-semibold text-slate-800 leading-tight">{{ $v->category->name }}</p>
                                @if($v->notes)
                                    <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-1 italic">"{{ $v->notes }}"</p>
                                @endif
                            </td>

                            <!-- Poin -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-lg font-black text-rose-600 bg-rose-50 border border-rose-100">
                                    +{{ $v->category->points }}
                                </span>
                            </td>

                            <!-- Pelapor (Klik untuk detail / modal) -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-1.5 group">
                                    <a href="{{ route('admin.users.show', $v->reporter->id) }}" 
                                       class="font-semibold text-slate-700 group-hover:text-indigo-600 group-hover:underline transition inline-flex items-center gap-1"
                                       title="Klik untuk membuka profil pelapor {{ $v->reporter->name }}">
                                        <span>{{ $v->reporter->name }}</span>
                                    </a>
                                    <button 
                                        type="button" 
                                        onclick="openUserQuickModal({{ json_encode([
                                            'id' => $v->reporter->id,
                                            'name' => $v->reporter->name,
                                            'nip' => $v->reporter->nip,
                                            'role' => $v->reporter->role,
                                            'position' => $v->reporter->position ?? '-',
                                            'phone' => $v->reporter->phone ?? '-',
                                            'domisili' => $v->reporter->domisili ?? '-',
                                            'email' => $v->reporter->email ?? '-',
                                            'avatar_url' => $v->reporter->avatar_url,
                                            'is_active' => $v->reporter->is_active,
                                            'show_url' => route('admin.users.show', $v->reporter->id),
                                            'edit_url' => route('admin.users.edit', $v->reporter->id),
                                        ]) }})"
                                        class="p-1 rounded-md text-slate-300 hover:text-indigo-600 hover:bg-indigo-50 transition" 
                                        title="Pratinjau cepat akun">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </button>
                                </div>
                                <span class="text-[10px] text-slate-400 font-mono block">NIP: {{ $v->reporter->nip }}</span>
                            </td>

                            <!-- Validator BK (Klik untuk detail / modal) -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($v->validator)
                                    <div class="flex items-center gap-1.5 group">
                                        <a href="{{ route('admin.users.show', $v->validator->id) }}" 
                                           class="font-semibold text-indigo-600 group-hover:text-indigo-900 group-hover:underline transition inline-flex items-center gap-1"
                                           title="Klik untuk membuka profil validator BK {{ $v->validator->name }}">
                                            <span>{{ $v->validator->name }}</span>
                                        </a>
                                        <button 
                                            type="button" 
                                            onclick="openUserQuickModal({{ json_encode([
                                                'id' => $v->validator->id,
                                                'name' => $v->validator->name,
                                                'nip' => $v->validator->nip,
                                                'role' => $v->validator->role,
                                                'position' => $v->validator->position ?? '-',
                                                'phone' => $v->validator->phone ?? '-',
                                                'domisili' => $v->validator->domisili ?? '-',
                                                'email' => $v->validator->email ?? '-',
                                                'avatar_url' => $v->validator->avatar_url,
                                                'is_active' => $v->validator->is_active,
                                                'show_url' => route('admin.users.show', $v->validator->id),
                                                'edit_url' => route('admin.users.edit', $v->validator->id),
                                            ]) }})"
                                            class="p-1 rounded-md text-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition" 
                                            title="Pratinjau cepat akun">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <span class="text-[10px] text-slate-400 font-mono block">NIP: {{ $v->validator->nip }}</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">Tidak ada riwayat pelanggaran sesuai filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- SEKSI 1: Mobile Card List View (Clean & No Horizontal Scroll) -->
        <div class="md:hidden divide-y divide-slate-100">
            @forelse($violations as $v)
                <div class="p-4 space-y-2.5">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                                    {{ $v->student->schoolClass->name }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $v->violation_date->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 mt-1">
                                <a href="{{ route('admin.students.show', $v->student->id) }}" class="font-bold text-slate-900 text-sm hover:text-indigo-600 truncate">
                                    {{ $v->student->name }}
                                </a>
                                <button 
                                    type="button" 
                                    onclick="openStudentQuickModal({{ json_encode([
                                        'id' => $v->student->id,
                                        'name' => $v->student->name,
                                        'nisn' => $v->student->nisn,
                                        'nis' => $v->student->nis ?? '-',
                                        'class_name' => $v->student->schoolClass->name ?? '-',
                                        'gender' => $v->student->gender === 'L' ? 'Laki-laki (L)' : 'Perempuan (P)',
                                        'points' => $v->student->total_violation_points,
                                        'parent_name' => $v->student->parent_name ?? '-',
                                        'parent_phone' => $v->student->parent_phone ?? '-',
                                        'show_url' => route('admin.students.show', $v->student->id),
                                        'edit_url' => route('admin.students.edit', $v->student->id),
                                    ]) }})"
                                    class="p-1 rounded text-slate-400 hover:text-indigo-600 bg-slate-50 border border-slate-200"
                                    title="Pratinjau cepat">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="text-right flex-shrink-0">
                            <span class="px-2.5 py-1 rounded-xl font-black text-xs text-rose-600 bg-rose-50 border border-rose-100 inline-block">
                                +{{ $v->category->points }} Poin
                            </span>
                        </div>
                    </div>

                    <!-- Pelanggaran Box -->
                    <div class="bg-slate-50/80 rounded-xl p-2.5 border border-slate-100 text-xs">
                        <span class="font-bold text-slate-800 block">{{ $v->category->name }}</span>
                        @if($v->notes)
                            <p class="text-[11px] text-slate-500 italic mt-0.5">"{{ $v->notes }}"</p>
                        @endif
                    </div>

                    <!-- Pelapor & Validator Footnotes -->
                    <div class="flex items-center justify-between text-[11px] text-slate-500 pt-0.5 flex-wrap gap-2">
                        <div class="flex items-center gap-1">
                            <span>Pelapor:</span>
                            <a href="{{ route('admin.users.show', $v->reporter->id) }}" class="font-semibold text-slate-800 hover:text-indigo-600 hover:underline">
                                {{ $v->reporter->name }}
                            </a>
                        </div>
                        @if($v->validator)
                            <div class="flex items-center gap-1">
                                <span>Validator BK:</span>
                                <a href="{{ route('admin.users.show', $v->validator->id) }}" class="font-semibold text-indigo-600 hover:underline">
                                    {{ $v->validator->name }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 text-xs">
                    Tidak ada riwayat pelanggaran sesuai filter.
                </div>
            @endforelse
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $violations->links() }}
        </div>
    </div>

    <!-- SEKSI 2: Rekapitulasi Sesi Bimbingan Konseling -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">2. Riwayat Sesi Bimbingan Konseling (BK)</h3>
                <p class="text-xs text-slate-400">Seluruh catatan pendampingan dan pembinaan perilaku siswa.</p>
            </div>
            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full font-bold text-xs">
                Total: {{ $counselings->total() }} Sesi
            </span>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Siswa & Kelas</th>
                        <th class="py-3 px-4">Konselor BK</th>
                        <th class="py-3 px-4">Permasalahan</th>
                        <th class="py-3 px-4">Hasil Pembinaan</th>
                        <th class="py-3 px-4">Rencana Tindak Lanjut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($counselings as $c)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap">{{ $c->counseling_date->format('d/m/Y') }}</td>
                            
                            <!-- Siswa (Klik untuk detail) -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <a href="{{ route('admin.students.show', $c->student->id) }}" 
                                   class="font-bold text-slate-800 hover:text-indigo-600 hover:underline block"
                                   title="Buka detail siswa {{ $c->student->name }}">
                                    {{ $c->student->name }}
                                </a>
                                <span class="text-[11px] text-slate-400">{{ $c->student->schoolClass->name }}</span>
                            </td>

                            <!-- Konselor BK (Klik untuk detail) -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <a href="{{ route('admin.users.show', $c->counselor->id) }}" 
                                   class="text-indigo-700 hover:text-indigo-900 font-semibold hover:underline block"
                                   title="Buka profil konselor {{ $c->counselor->name }}">
                                    {{ $c->counselor->name }}
                                </a>
                                <span class="text-[10px] text-slate-400 font-mono">NIP: {{ $c->counselor->nip }}</span>
                            </td>

                            <td class="py-3.5 px-4 max-w-xs text-slate-700">{{ $c->issue_summary }}</td>
                            <td class="py-3.5 px-4 max-w-xs text-slate-600">{{ $c->counseling_result }}</td>
                            <td class="py-3.5 px-4 max-w-xs text-slate-600">{{ $c->follow_up_action }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Tidak ada data konseling sesuai filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card List View (Clean & No Horizontal Scroll) -->
        <div class="md:hidden divide-y divide-slate-100">
            @forelse($counselings as $c)
                <div class="p-4 space-y-2.5">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <span class="text-[10px] text-slate-400 font-mono">{{ $c->counseling_date->format('d/m/Y') }}</span>
                            <a href="{{ route('admin.students.show', $c->student->id) }}" class="font-bold text-slate-900 text-sm hover:text-indigo-600 block mt-0.5 truncate">
                                {{ $c->student->name }}
                            </a>
                            <span class="text-[11px] text-slate-500">{{ $c->student->schoolClass->name }}</span>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="text-[10px] text-slate-400 block">Konselor:</span>
                            <a href="{{ route('admin.users.show', $c->counselor->id) }}" class="font-semibold text-xs text-indigo-600 hover:underline block truncate max-w-[130px]">
                                {{ $c->counselor->name }}
                            </a>
                        </div>
                    </div>

                    <div class="bg-slate-50/80 rounded-xl p-3 border border-slate-100 text-xs space-y-1.5">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Permasalahan:</span>
                            <p class="text-slate-800 font-medium">{{ $c->issue_summary }}</p>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Hasil Pembinaan:</span>
                            <p class="text-slate-700">{{ $c->counseling_result }}</p>
                        </div>
                        @if($c->follow_up_action)
                            <div class="pt-1 border-t border-slate-200/60">
                                <span class="text-[10px] font-bold text-emerald-700 uppercase block">Tindak Lanjut:</span>
                                <p class="text-emerald-900 font-medium">{{ $c->follow_up_action }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 text-xs">
                    Tidak ada data konseling sesuai filter.
                </div>
            @endforelse
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $counselings->links() }}
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL 1: QUICK VIEW SISWA                                -->
<!-- ======================================================== -->
<div id="student-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs opacity-0 invisible pointer-events-none transition-all duration-200">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-100 overflow-hidden transform scale-95 transition-transform duration-200" id="student-modal-card">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">🎓</span>
                <h4 class="font-bold text-slate-900 text-sm">Pratinjau Rekam Jejak Siswa</h4>
            </div>
            <button type="button" onclick="closeStudentQuickModal()" class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-200/70 flex items-center justify-center transition">
                &times;
            </button>
        </div>
        
        <div class="p-5 space-y-4">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 id="modal-student-name" class="text-lg font-bold text-slate-900"></h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        <span id="modal-student-class" class="font-semibold text-slate-700"></span> • 
                        <span id="modal-student-gender" class="text-slate-500"></span>
                    </p>
                    <p class="text-xs text-slate-400 font-mono mt-0.5">
                        NISN: <span id="modal-student-nisn" class="text-slate-700 font-semibold"></span> • 
                        NIS: <span id="modal-student-nis" class="text-slate-700"></span>
                    </p>
                </div>
                
                <div class="text-right flex-shrink-0 bg-slate-50 px-3 py-2 rounded-xl border border-slate-100">
                    <span class="text-[10px] font-bold uppercase text-slate-400 block">Total Poin</span>
                    <span id="modal-student-points" class="text-xl font-black text-rose-600 block"></span>
                </div>
            </div>

            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-1.5 text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-500">Orang Tua/Wali:</span>
                    <span id="modal-student-parent" class="font-semibold text-slate-800"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Kontak Telepon:</span>
                    <span id="modal-student-phone" class="font-mono text-slate-800"></span>
                </div>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2">
                <a id="modal-student-edit-btn" href="#" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span>Edit Siswa</span>
                </a>
                <a id="modal-student-show-btn" href="#" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                    <span>Lihat Halaman Lengkap</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL 2: QUICK VIEW USER / PELAPOR                       -->
<!-- ======================================================== -->
<div id="user-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs opacity-0 invisible pointer-events-none transition-all duration-200">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-100 overflow-hidden transform scale-95 transition-transform duration-200" id="user-modal-card">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">👤</span>
                <h4 class="font-bold text-slate-900 text-sm">Pratinjau Akun Pengguna</h4>
            </div>
            <button type="button" onclick="closeUserQuickModal()" class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-200/70 flex items-center justify-center transition">
                &times;
            </button>
        </div>
        
        <div class="p-5 space-y-4">
            <div class="flex items-center gap-4">
                <img id="modal-user-avatar" src="" alt="Avatar" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-slate-100 shadow-sm flex-shrink-0">
                <div class="min-w-0 flex-1">
                    <h3 id="modal-user-name" class="text-base font-bold text-slate-900 truncate"></h3>
                    <div class="flex items-center gap-2 mt-1">
                        <span id="modal-user-role" class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider"></span>
                        <span id="modal-user-status" class="px-2 py-0.5 rounded-full text-[10px] font-semibold"></span>
                    </div>
                    <p class="text-xs text-slate-400 font-mono mt-1">
                        NIP: <span id="modal-user-nip" class="text-slate-700 font-semibold"></span>
                    </p>
                </div>
            </div>

            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-1.5 text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-500">Jabatan:</span>
                    <span id="modal-user-position" class="text-slate-800 font-semibold truncate max-w-[220px]"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Kontak HP/WA:</span>
                    <span id="modal-user-phone" class="font-mono text-slate-800 font-semibold"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Email:</span>
                    <span id="modal-user-email" class="text-slate-800 font-medium truncate max-w-[220px]"></span>
                </div>
                <div class="pt-1 border-t border-slate-200/60">
                    <span class="text-slate-500 block mb-0.5">Alamat Domisili:</span>
                    <p id="modal-user-domisili" class="text-slate-700 font-medium leading-relaxed italic"></p>
                </div>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2">
                <a id="modal-user-edit-btn" href="#" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span>Edit Akun</span>
                </a>
                <a id="modal-user-show-btn" href="#" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                    <span>Lihat Profil & Aktivitas</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function openStudentQuickModal(data) {
        document.getElementById('modal-student-name').textContent = data.name;
        document.getElementById('modal-student-class').textContent = data.class_name;
        document.getElementById('modal-student-gender').textContent = data.gender;
        document.getElementById('modal-student-nisn').textContent = data.nisn;
        document.getElementById('modal-student-nis').textContent = data.nis;
        document.getElementById('modal-student-points').textContent = data.points + ' Poin';
        document.getElementById('modal-student-parent').textContent = data.parent_name;
        document.getElementById('modal-student-phone').textContent = data.parent_phone;
        document.getElementById('modal-student-show-btn').href = data.show_url;
        document.getElementById('modal-student-edit-btn').href = data.edit_url;

        const modal = document.getElementById('student-modal');
        const card = document.getElementById('student-modal-card');
        modal.classList.remove('opacity-0', 'invisible', 'pointer-events-none');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
    }

    function closeStudentQuickModal() {
        const modal = document.getElementById('student-modal');
        const card = document.getElementById('student-modal-card');
        modal.classList.add('opacity-0', 'invisible', 'pointer-events-none');
        card.classList.remove('scale-100');
        card.classList.add('scale-95');
    }

    function openUserQuickModal(data) {
        document.getElementById('modal-user-name').textContent = data.name;
        document.getElementById('modal-user-nip').textContent = data.nip;
        document.getElementById('modal-user-position').textContent = data.position || '-';
        document.getElementById('modal-user-phone').textContent = data.phone || '-';
        document.getElementById('modal-user-domisili').textContent = data.domisili || 'Belum ada data domisili.';
        document.getElementById('modal-user-email').textContent = data.email;
        document.getElementById('modal-user-avatar').src = data.avatar_url;
        document.getElementById('modal-user-avatar').onerror = function() {
            this.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(data.name) + '&background=0f172a&color=ffffff&size=128&bold=true';
        };

        const roleElem = document.getElementById('modal-user-role');
        roleElem.textContent = data.role === 'admin' ? 'Super Admin' : (data.role === 'bk' ? 'Guru BK' : 'Guru Biasa');
        roleElem.className = 'px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider ' + 
            (data.role === 'admin' ? 'bg-red-100 text-red-700' : (data.role === 'bk' ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700'));

        const statusElem = document.getElementById('modal-user-status');
        statusElem.textContent = data.is_active ? 'Aktif' : 'Nonaktif';
        statusElem.className = 'px-2 py-0.5 rounded-full text-[10px] font-semibold ' + 
            (data.is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500');

        document.getElementById('modal-user-show-btn').href = data.show_url;
        document.getElementById('modal-user-edit-btn').href = data.edit_url;

        const modal = document.getElementById('user-modal');
        const card = document.getElementById('user-modal-card');
        modal.classList.remove('opacity-0', 'invisible', 'pointer-events-none');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
    }

    function closeUserQuickModal() {
        const modal = document.getElementById('user-modal');
        const card = document.getElementById('user-modal-card');
        modal.classList.add('opacity-0', 'invisible', 'pointer-events-none');
        card.classList.remove('scale-100');
        card.classList.add('scale-95');
    }

    // Close on backdrop click
    document.getElementById('student-modal').addEventListener('click', function(e) {
        if (e.target === this) closeStudentQuickModal();
    });
    document.getElementById('user-modal').addEventListener('click', function(e) {
        if (e.target === this) closeUserQuickModal();
    });

    // Close on ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeStudentQuickModal();
            closeUserQuickModal();
        }
    });
</script>
@endsection
