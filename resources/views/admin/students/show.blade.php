@extends('layouts.app')

@section('title', 'Rekam Jejak Siswa: ' . $student->name)
@section('page_title', 'Profil & Histori Rekam Jejak Siswa')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Back buttons -->
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-2 text-xs">
            <a href="{{ route('admin.students.index') }}" class="font-semibold text-slate-500 hover:text-slate-800 transition flex items-center gap-1">
                <span>&larr;</span> Data Siswa
            </a>
            <span class="text-slate-300">/</span>
            <a href="{{ route('admin.reports.index') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 transition">
                Laporan Global
            </a>
            <span class="text-slate-300">/</span>
            <span class="text-slate-700 font-bold truncate">{{ $student->name }}</span>
        </div>

        <a href="{{ route('admin.students.edit', $student->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition shadow-xs">
            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            <span>Edit Data Siswa</span>
        </a>
    </div>

    <!-- Student Profile Card & Total Points Counter -->
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-700 font-extrabold text-xl flex items-center justify-center flex-shrink-0 shadow-xs">
                {{ strtoupper(substr($student->name, 0, 2)) }}
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900">{{ $student->name }}</h2>
                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        {{ $student->schoolClass->name }}
                    </span>
                    <span class="px-2 py-0.5 rounded-lg text-[11px] font-semibold bg-indigo-50 text-indigo-700">
                        {{ $student->schoolClass->major->name ?? '' }}
                    </span>
                </div>
                
                <p class="text-xs text-slate-500 mt-1.5 font-mono">
                    NISN: <strong class="text-slate-700">{{ $student->nisn }}</strong> • NIS: <strong class="text-slate-700">{{ $student->nis ?? '-' }}</strong> • Jenis Kelamin: <span class="font-medium text-slate-700">{{ $student->gender === 'L' ? 'Laki-laki (L)' : 'Perempuan (P)' }}</span>
                </p>
                
                <div class="flex items-center gap-3 mt-2 text-xs text-slate-600 flex-wrap">
                    <span>Orang Tua/Wali: <strong class="text-slate-800">{{ $student->parent_name ?? '-' }}</strong></span>
                    <span>•</span>
                    <span>No. Kontak: 
                        @if($student->parent_phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->parent_phone) }}" target="_blank" class="font-mono text-emerald-600 hover:underline font-bold">
                                📞 {{ $student->parent_phone }}
                            </a>
                        @else
                            <span class="text-slate-400 font-mono">-</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Akumulasi Poin Sanksi Badge -->
        <div class="flex flex-col items-start md:items-end justify-center bg-slate-50 p-4 rounded-xl border border-slate-200/80 w-full md:w-auto md:min-w-[200px]">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Total Poin Sanksi</span>
            <span class="text-3xl font-black block mt-0.5
                {{ $student->total_violation_points >= 50 ? 'text-rose-600' : ($student->total_violation_points >= 20 ? 'text-amber-600' : 'text-slate-800') }}">
                {{ $student->total_violation_points }} Poin
            </span>
            <span class="text-[11px] font-bold mt-1 px-2.5 py-0.5 rounded-full block
                {{ $student->total_violation_points >= 50 ? 'bg-rose-100 text-rose-700 border border-rose-200' : ($student->total_violation_points >= 20 ? 'bg-amber-100 text-amber-700 border border-amber-200' : 'bg-emerald-100 text-emerald-700 border border-emerald-200') }}">
                {{ $student->total_violation_points >= 50 ? 'Status: Kritis (Pemanggilan)' : ($student->total_violation_points >= 20 ? 'Status: Peringatan' : 'Status: Normal') }}
            </span>
        </div>
    </div>

    <!-- 2 Kolom: Histori Pelanggaran & Riwayat Konseling -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- 1. Riwayat Kejadian Pelanggaran -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Riwayat Kejadian Pelanggaran</h3>
                        <p class="text-xs text-slate-400">Seluruh pencatatan pelanggaran siswa ini</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100">
                        {{ $student->violations->count() }} Kasus
                    </span>
                </div>

                <div class="space-y-3">
                    @forelse($student->violations as $v)
                        <div class="p-3.5 rounded-xl border {{ $v->status === 'validated' ? 'bg-slate-50/70 border-slate-200' : ($v->status === 'pending' ? 'bg-amber-50/40 border-amber-200' : 'bg-rose-50/40 border-rose-200') }} text-xs">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="font-bold text-slate-900">{{ $v->category->name }}</span>
                                <div class="flex items-center gap-1.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black text-rose-600 bg-rose-50 border border-rose-100">
                                        +{{ $v->category->points }} Poin
                                    </span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                        {{ $v->status === 'validated' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                        {{ $v->status === 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                                        {{ $v->status === 'rejected' ? 'bg-rose-100 text-rose-800' : '' }}">
                                        {{ $v->status }}
                                    </span>
                                </div>
                            </div>

                            @if($v->notes)
                                <p class="text-slate-600 mb-2 italic bg-white/70 p-2 rounded-lg border border-slate-100">"{{ $v->notes }}"</p>
                            @endif

                            <div class="pt-2 border-t border-slate-200/80 flex items-center justify-between text-[11px] text-slate-500 flex-wrap gap-2">
                                <span>Tanggal: <strong class="text-slate-700">{{ $v->violation_date->format('d/m/Y') }}</strong></span>
                                <div class="flex items-center gap-2">
                                    <span>Pelapor: 
                                        <a href="{{ route('admin.users.show', $v->reporter->id) }}" class="font-semibold text-indigo-600 hover:underline">
                                            {{ $v->reporter->name }}
                                        </a>
                                    </span>
                                    @if($v->validator)
                                        <span>• Validator: 
                                            <a href="{{ route('admin.users.show', $v->validator->id) }}" class="font-semibold text-slate-700 hover:underline">
                                                {{ $v->validator->name }}
                                            </a>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-400">
                            <p class="text-xs">Siswa ini belum memiliki catatan pelanggaran.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- 2. Riwayat Bimbingan Konseling -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Riwayat Bimbingan Konseling (BK)</h3>
                        <p class="text-xs text-slate-400">Catatan pembinaan dan konseling berkala</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                        {{ $student->counselingRecords->count() }} Sesi
                    </span>
                </div>

                <div class="space-y-3">
                    @forelse($student->counselingRecords as $c)
                        <div class="p-3.5 rounded-xl bg-slate-50/70 border border-slate-200 text-xs space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-800">Tanggal: {{ $c->counseling_date->format('d/m/Y') }}</span>
                                <span class="text-[11px] text-slate-500">
                                    Konselor: 
                                    <a href="{{ route('admin.users.show', $c->counselor->id) }}" class="font-bold text-indigo-700 hover:underline">
                                        {{ $c->counselor->name }}
                                    </a>
                                </span>
                            </div>

                            <div class="space-y-1.5 pt-1">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Permasalahan</span>
                                    <p class="text-slate-800 bg-white p-2 rounded-lg border border-slate-100">{{ $c->issue_summary }}</p>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Hasil Pembinaan</span>
                                    <p class="text-slate-700 bg-white p-2 rounded-lg border border-slate-100">{{ $c->counseling_result }}</p>
                                </div>
                                @if($c->follow_up_action)
                                    <div>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Rencana Tindak Lanjut</span>
                                        <p class="text-slate-700 bg-white p-2 rounded-lg border border-slate-100">{{ $c->follow_up_action }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-400">
                            <p class="text-xs">Belum ada riwayat konseling untuk siswa ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
