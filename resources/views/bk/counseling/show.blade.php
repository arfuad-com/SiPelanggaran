@extends('layouts.app')

@section('title', 'Detail Bimbingan Konseling')
@section('page_title', 'Lembar Catatan Sesi Bimbingan Konseling')

@section('content')
<div class="max-w-3xl space-y-6">
    <a href="{{ route('bk.counseling.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 transition">
        &larr; Kembali ke Daftar Konseling
    </a>

    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-6">
        <!-- Header Info -->
        <div class="flex items-center justify-between pb-6 border-b border-slate-100">
            <div>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md">
                    Sesi Konseling Siswa
                </span>
                <h2 class="text-2xl font-black text-slate-800 mt-2">{{ $counseling->student->name }}</h2>
                <p class="text-xs text-slate-400 mt-1">
                    Kelas: {{ $counseling->student->schoolClass->name }} • NISN: {{ $counseling->student->nisn }}
                </p>
            </div>
            <div class="text-right">
                <span class="text-xs text-slate-400 block font-medium">Tanggal Konseling</span>
                <span class="font-bold text-slate-800 text-base mt-0.5 block">{{ $counseling->counseling_date->format('d F Y') }}</span>
                <span class="text-xs text-indigo-600 font-semibold mt-1 block">Oleh: {{ $counseling->counselor->name }}</span>
            </div>
        </div>

        <!-- 3 Pillars of Counseling -->
        <div class="space-y-4">
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">1. Pokok Permasalahan yang Dihadapi</h4>
                <p class="text-sm text-slate-800 leading-relaxed">{{ $counseling->issue_summary }}</p>
            </div>

            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">2. Hasil Pembinaan & Kesepakatan</h4>
                <p class="text-sm text-slate-800 leading-relaxed">{{ $counseling->counseling_result }}</p>
            </div>

            <div class="p-4 bg-emerald-50/50 rounded-2xl border border-emerald-100">
                <h4 class="text-xs font-bold text-emerald-700 uppercase tracking-wider mb-1.5">3. Rencana Tindak Lanjut</h4>
                <p class="text-sm text-emerald-950 font-medium leading-relaxed">{{ $counseling->follow_up_action }}</p>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
            <a href="{{ route('bk.students.show', $counseling->student->id) }}" class="text-xs font-bold text-indigo-600 hover:underline">
                Buka Rekam Jejak Pelanggaran Siswa Ini &rarr;
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-700 transition">
                🖨️ Cetak Lembar Konseling
            </button>
        </div>
    </div>
</div>
@endsection
