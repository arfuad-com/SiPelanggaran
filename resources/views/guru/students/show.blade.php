@extends('layouts.app')

@section('title', 'Histori Siswa: ' . $student->name)
@section('page_title', 'Profil & Histori Pelanggaran Siswa')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <a href="{{ route('guru.students.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
        &larr; Kembali ke Direktori Siswa
    </a>

    <!-- Student Header Card -->
    <div class="bg-white rounded-xl p-5 border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-5 shadow-xs">
        <div>
            <div class="flex items-center gap-2 flex-wrap">
                <h2 class="text-xl font-bold text-slate-900">{{ $student->name }}</h2>
                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                    {{ $student->schoolClass->name }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1 font-mono">
                NISN: {{ $student->nisn }} • Jenis Kelamin: {{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
            </p>
        </div>

        <div class="p-3.5 bg-slate-50 rounded-lg border border-slate-200 text-left sm:text-right">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Total Poin Sanksi</span>
            <span class="text-2xl sm:text-3xl font-extrabold block mt-0.5
                {{ $student->total_violation_points >= 50 ? 'text-rose-600' : ($student->total_violation_points > 0 ? 'text-amber-600' : 'text-slate-700') }}">
                {{ $student->total_violation_points }} Poin
            </span>
        </div>
    </div>

    <!-- Riwayat Pelanggaran (Read Only) -->
    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs">
        <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Catatan Pelanggaran Terverifikasi</h3>
                <p class="text-xs text-slate-500">Pelanggaran yang telah divalidasi oleh Guru BK</p>
            </div>
            <span class="text-xs text-slate-400 font-medium">{{ $student->violations->count() }} Kasus</span>
        </div>

        <div class="space-y-3">
            @forelse($student->violations as $v)
                <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200 text-xs">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="font-bold text-slate-900">{{ $v->category->name }}</span>
                        <span class="font-bold text-rose-600 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded text-[11px]">
                            +{{ $v->category->points }} Poin
                        </span>
                    </div>
                    <p class="text-slate-600 mb-2">"{{ $v->notes ?? '-' }}"</p>
                    <div class="pt-2 border-t border-slate-200 flex items-center justify-between text-[11px] text-slate-400">
                        <span>Tanggal: {{ $v->violation_date->format('d/m/Y') }}</span>
                        <span>Pelapor: {{ $v->reporter->name }}</span>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 py-8 text-center font-medium">Belum ada riwayat pelanggaran tervalidasi untuk siswa ini.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
