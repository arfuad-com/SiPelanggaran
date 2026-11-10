@extends('layouts.app')

@section('title', 'Rekam Jejak: ' . $student->name)
@section('page_title', 'Profil & Histori Rekam Jejak Siswa')

@section('content')
<div class="space-y-5">
    <a href="{{ route('bk.students.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
        &larr; Kembali ke Direktori Siswa
    </a>

    <!-- Top Card: Student Info & Points Counter -->
    <div class="bg-white rounded-xl p-5 sm:p-6 border border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-5">
        <div>
            <div class="flex items-center gap-2 flex-wrap">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900">{{ $student->name }}</h2>
                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                    {{ $student->schoolClass->name }}
                </span>
            </div>
            
            <p class="text-xs text-slate-500 mt-1 font-mono">
                NISN: {{ $student->nisn }} • NIS: {{ $student->nis ?? '-' }} • Jenis Kelamin: {{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
            </p>
            
            <p class="text-xs text-slate-600 mt-2">
                Orang Tua / Wali: <strong class="text-slate-800">{{ $student->parent_name ?? '-' }}</strong> • No. Kontak: <span class="font-mono text-slate-800">{{ $student->parent_phone ?? '-' }}</span>
            </p>
        </div>

        <!-- Akumulasi Poin Sanksi Badge -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 bg-slate-50 p-4 rounded-lg border border-slate-200">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Total Poin Sanksi</span>
                <span class="text-2xl sm:text-3xl font-extrabold block mt-0.5
                    {{ $student->total_violation_points >= 50 ? 'text-rose-600' : ($student->total_violation_points > 0 ? 'text-amber-600' : 'text-slate-700') }}">
                    {{ $student->total_violation_points }} Poin
                </span>
                <span class="text-[11px] font-medium mt-0.5 block
                    {{ $student->total_violation_points >= 50 ? 'text-rose-600' : ($student->total_violation_points >= 20 ? 'text-amber-600' : 'text-emerald-700') }}">
                    {{ $student->total_violation_points >= 50 ? 'Status: Kritis (Pemanggilan)' : ($student->total_violation_points >= 20 ? 'Status: Peringatan' : 'Status: Normal') }}
                </span>
            </div>

            <a href="{{ route('bk.counseling.create', ['student_id' => $student->id]) }}" class="px-3.5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-xs font-semibold transition whitespace-nowrap">
                + Bimbingan Konseling
            </a>
        </div>
    </div>

    <!-- Grid: Histori Pelanggaran & Riwayat Konseling -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        <!-- 1. Riwayat Pelanggaran -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Riwayat Kejadian Pelanggaran</h3>
                    <span class="text-xs text-slate-500 font-medium">{{ $student->violations->count() }} Kasus</span>
                </div>

                <div class="space-y-3">
                    @forelse($student->violations as $v)
                        <div class="p-3.5 rounded-lg border {{ $v->status === 'validated' ? 'bg-slate-50 border-slate-200' : ($v->status === 'pending' ? 'bg-amber-50/50 border-amber-200' : 'bg-rose-50/40 border-rose-200') }} text-xs">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="font-bold text-slate-900">{{ $v->category->name }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                    {{ $v->status === 'validated' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                    {{ $v->status === 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $v->status === 'rejected' ? 'bg-rose-100 text-rose-800' : '' }}">
                                    {{ $v->status }}
                                </span>
                            </div>

                            <p class="text-slate-600 mb-2">"{{ $v->notes ?? 'Tidak ada catatan.' }}"</p>

                            <div class="pt-2 border-t border-slate-200 flex items-center justify-between text-[11px] text-slate-500">
                                <span>Tanggal: {{ $v->violation_date->format('d/m/Y') }} • Pelapor: {{ $v->reporter->name }}</span>
                                <span class="font-bold text-rose-600">+{{ $v->category->points }} Poin</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-8 text-center font-medium">Siswa ini belum memiliki catatan pelanggaran.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- 2. Riwayat Bimbingan Konseling -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Riwayat Bimbingan Konseling (BK)</h3>
                    <span class="text-xs text-slate-500 font-medium">{{ $student->counselingRecords->count() }} Sesi</span>
                </div>

                <div class="space-y-3">
                    @forelse($student->counselingRecords as $c)
                        <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200 text-xs space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-900">Tanggal Sesi: {{ $c->counseling_date->format('d/m/Y') }}</span>
                                <span class="text-[11px] text-slate-500">Konselor: {{ $c->counselor->name }}</span>
                            </div>

                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Pokok Permasalahan:</span>
                                <p class="text-slate-800 font-medium">{{ $c->issue_summary }}</p>
                            </div>

                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Hasil Pembinaan:</span>
                                <p class="text-slate-600">{{ $c->counseling_result }}</p>
                            </div>

                            <div class="pt-1.5 border-t border-slate-200">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Rencana Tindak Lanjut:</span>
                                <p class="text-emerald-800 font-medium">{{ $c->follow_up_action }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-400">
                            <p class="text-xs font-medium">Belum ada sesi bimbingan konseling tercatat untuk siswa ini.</p>
                            <a href="{{ route('bk.counseling.create', ['student_id' => $student->id]) }}" class="inline-block mt-3 px-3 py-1.5 bg-slate-100 text-slate-700 font-semibold text-xs rounded-lg hover:bg-slate-200 transition">
                                + Catat Sesi Konseling Baru
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
