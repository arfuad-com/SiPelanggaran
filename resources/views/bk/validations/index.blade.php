@extends('layouts.app')

@section('title', 'Validasi Laporan Pelanggaran')
@section('page_title', 'Validasi Laporan Pelanggaran Siswa')

@section('content')
<div class="space-y-6">
    <!-- Header Page & Quick Stats -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 text-sm font-bold">
                    ⚖️
                </span>
                <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">Antrean Validasi Laporan Masuk</h3>
            </div>
            <p class="text-xs sm:text-sm text-slate-500">
                Tinjau dan putuskan persetujuan laporan pelanggaran yang diajukan oleh guru mata pelajaran dan wali kelas.
            </p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl {{ $pendingViolations->total() > 0 ? 'bg-amber-50 border-amber-200 text-amber-800' : 'bg-emerald-50 border-emerald-200 text-emerald-800' }} border text-xs font-bold">
                <span class="inline-block w-2 h-2 rounded-full {{ $pendingViolations->total() > 0 ? 'bg-amber-500 animate-pulse' : 'bg-emerald-500' }}"></span>
                <span>{{ $pendingViolations->total() }} Menunggu Tindak Lanjut</span>
            </div>

            <a href="{{ route('bk.violations.create') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-xs font-bold transition shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Input Kasus Sendiri</span>
            </a>
        </div>
    </div>

    <!-- Validations List Card -->
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Daftar Antrean Masuk</h4>
            </div>
            <span class="text-xs text-slate-400 font-medium">Halaman {{ $pendingViolations->currentPage() }} dari {{ max(1, $pendingViolations->lastPage()) }}</span>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-5">Waktu Lapor</th>
                        <th class="py-3.5 px-5">Siswa & Kelas</th>
                        <th class="py-3.5 px-5">Jenis Pelanggaran</th>
                        <th class="py-3.5 px-5 text-center">Bobot</th>
                        <th class="py-3.5 px-5">Guru Pelapor</th>
                        <th class="py-3.5 px-5">Kronologi Kasus</th>
                        <th class="py-3.5 px-5 text-right">Aksi Validasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pendingViolations as $item)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-4 px-5 text-slate-500 whitespace-nowrap align-top">
                                <span class="font-bold text-slate-800 block text-xs">{{ $item->violation_date->format('d/m/Y') }}</span>
                                <span class="text-[11px] text-slate-400 font-mono">{{ $item->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="py-4 px-5 whitespace-nowrap align-top">
                                <a href="{{ route('bk.students.show', $item->student->id) }}" class="font-bold text-indigo-600 hover:text-indigo-800 hover:underline block text-xs sm:text-sm">
                                    {{ $item->student->name }}
                                </a>
                                <div class="flex items-center gap-2 mt-0.5 text-[11px] text-slate-500">
                                    <span class="font-semibold px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 border border-slate-200">
                                        {{ $item->student->schoolClass->name }}
                                    </span>
                                    <span>Poin: <strong class="text-slate-800">{{ $item->student->total_violation_points }}</strong></span>
                                </div>
                            </td>
                            <td class="py-4 px-5 align-top">
                                <span class="font-bold text-slate-800 block leading-snug">{{ $item->category->name }}</span>
                                <span class="text-[11px] text-slate-400">Kode: #{{ $item->category->id }}</span>
                            </td>
                            <td class="py-4 px-5 text-center whitespace-nowrap align-top">
                                <span class="inline-block px-2.5 py-1 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 font-extrabold text-xs shadow-2xs">
                                    +{{ $item->category->points }} Poin
                                </span>
                            </td>
                            <td class="py-4 px-5 text-slate-700 font-semibold whitespace-nowrap align-top">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-600 flex-shrink-0">
                                        {{ strtoupper(substr($item->reporter->name, 0, 1)) }}
                                    </div>
                                    <span class="truncate max-w-[140px]">{{ $item->reporter->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-5 text-slate-600 align-top max-w-sm">
                                <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-100 text-[11px] leading-relaxed text-slate-700">
                                    "{{ $item->notes ?? 'Tidak ada uraian kejadian yang dilampirkan.' }}"
                                </div>
                            </td>
                            <td class="py-4 px-5 text-right whitespace-nowrap align-top space-x-1.5">
                                <!-- Tombol Setujui -->
                                <form method="POST" action="{{ route('bk.validations.process', $item->id) }}" class="inline" onsubmit="return confirm('Setujui laporan ini? Poin sanksi siswa akan otomatis bertambah.');">
                                    @csrf
                                    <input type="hidden" name="action" value="accept">
                                    <button type="submit" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white rounded-xl text-xs font-bold transition-all shadow-xs inline-flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <span>Setujui</span>
                                    </button>
                                </form>

                                <!-- Tombol Tolak -->
                                <form method="POST" action="{{ route('bk.validations.process', $item->id) }}" class="inline" onsubmit="return confirm('Tolak laporan ini? Poin siswa tidak akan bertambah.');">
                                    @csrf
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="px-3 py-2 bg-rose-50 hover:bg-rose-100 active:scale-95 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                        <span>Tolak</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center">
                                <div class="max-w-md mx-auto text-center">
                                    <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-3xl shadow-xs border border-emerald-100">
                                        ✨
                                    </div>
                                    <h4 class="font-extrabold text-slate-800 text-base">Semua Laporan Telah Tervalidasi</h4>
                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                        Tidak ada antrean pelanggaran yang menunggu validasi saat ini. Seluruh laporan guru mata pelajaran dan wali kelas telah selesai ditinjau.
                                    </p>
                                    <div class="mt-4 flex items-center justify-center gap-2">
                                        <a href="{{ route('bk.dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                                            Kembali ke Analitik
                                        </a>
                                        <a href="{{ route('bk.students.index') }}" class="px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-xl transition">
                                            Buka Rekam Jejak Siswa
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card List View (Clean & Touch-friendly) -->
        <div class="md:hidden divide-y divide-slate-100">
            @forelse($pendingViolations as $item)
                <div class="p-4 space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $item->student->schoolClass->name }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $item->violation_date->format('d/m/Y') }}</span>
                            </div>
                            <a href="{{ route('bk.students.show', $item->student->id) }}" class="font-bold text-slate-900 text-sm hover:text-indigo-600 block mt-1 truncate">
                                {{ $item->student->name }}
                            </a>
                            <p class="text-[11px] text-slate-400">Poin Saat Ini: <strong class="text-slate-700">{{ $item->student->total_violation_points }}</strong></p>
                        </div>
                        <span class="px-2.5 py-1 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 font-extrabold text-xs flex-shrink-0">
                            +{{ $item->category->points }} Poin
                        </span>
                    </div>

                    <div class="bg-slate-50/90 rounded-xl p-3 border border-slate-100 text-xs space-y-1.5">
                        <div class="flex items-center justify-between gap-1 text-[11px] text-slate-500">
                            <span>Pelanggaran:</span>
                            <span class="font-bold text-slate-800 text-right truncate">{{ $item->category->name }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-1 text-[11px] text-slate-500">
                            <span>Pelapor:</span>
                            <span class="font-semibold text-slate-700 text-right truncate">{{ $item->reporter->name }}</span>
                        </div>
                        @if($item->notes)
                            <div class="pt-1 border-t border-slate-200/60 text-[11px] text-slate-600 italic">
                                "{{ $item->notes }}"
                            </div>
                        @endif
                    </div>

                    <!-- Touch Actions -->
                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <form method="POST" action="{{ route('bk.validations.process', $item->id) }}" onsubmit="return confirm('Setujui laporan ini?');">
                            @csrf
                            <input type="hidden" name="action" value="accept">
                            <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white rounded-xl text-xs font-bold transition text-center shadow-xs">
                                ✓ Setujui (+{{ $item->category->points }})
                            </button>
                        </form>

                        <form method="POST" action="{{ route('bk.validations.process', $item->id) }}" onsubmit="return confirm('Tolak laporan ini?');">
                            @csrf
                            <input type="hidden" name="action" value="reject">
                            <button type="submit" class="w-full py-2.5 bg-rose-50 hover:bg-rose-100 active:scale-95 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition text-center">
                                ✕ Tolak
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <div class="w-14 h-14 mx-auto mb-2 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl border border-emerald-100">
                        ✨
                    </div>
                    <p class="font-bold text-slate-800 text-sm">Semua Laporan Telah Tervalidasi!</p>
                    <p class="text-xs text-slate-400 mt-1">Tidak ada antrean laporan yang menunggu saat ini.</p>
                </div>
            @endforelse
        </div>

        @if($pendingViolations->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $pendingViolations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
