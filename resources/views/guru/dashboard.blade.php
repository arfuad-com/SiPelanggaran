@extends('layouts.app')

@section('title', 'Dashboard Guru')
@section('page_title', 'Dashboard Guru Pelapor & Wali Kelas')

@section('content')
<div class="space-y-5">

    <!-- Header Action Section (Corporate, Clean) -->
    <div class="bg-slate-900 rounded-xl p-5 sm:p-6 text-white border border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-lg sm:text-xl font-bold tracking-tight text-white">
                Portal Pelaporan Perilaku Siswa
            </h1>
            <p class="text-xs text-slate-300 mt-1 max-w-xl leading-relaxed">
                Laporkan pelanggaran tata tertib yang Anda temui. Laporan akan otomatis diteruskan ke tim Guru BK untuk diverifikasi.
            </p>
        </div>

        <div>
            <a href="{{ route('guru.violations.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition">
                <span>+</span> Buat Laporan Pelanggaran
            </a>
        </div>
    </div>

    <!-- Statistik Guru Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Laporan</p>
            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">{{ $myTotalReports }}</p>
            <p class="text-[11px] text-slate-400 mt-0.5">Terkirim oleh Anda</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Pending</p>
            <p class="text-2xl sm:text-3xl font-extrabold text-amber-600 mt-1">{{ $myPendingReports }}</p>
            <p class="text-[11px] text-slate-400 mt-0.5">Menunggu respon BK</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Disetujui BK</p>
            <p class="text-2xl sm:text-3xl font-extrabold text-emerald-700 mt-1">{{ $myValidatedReports }}</p>
            <p class="text-[11px] text-emerald-700 font-medium mt-0.5">Poin sanksi aktif</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Ditolak BK</p>
            <p class="text-2xl sm:text-3xl font-extrabold text-slate-700 mt-1">{{ $myRejectedReports }}</p>
            <p class="text-[11px] text-slate-400 mt-0.5">Tidak valid</p>
        </div>
    </div>

    <!-- Recent Reports Table -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-900 text-sm">Laporan Terkini yang Anda Buat</h3>
                <p class="text-xs text-slate-500">Status verifikasi tindak lanjut oleh Guru BK</p>
            </div>
            <a href="{{ route('guru.violations.my-reports') }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                Semua Laporan &rarr;
            </a>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-600 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Siswa & Kelas</th>
                        <th class="py-3 px-4">Jenis Pelanggaran</th>
                        <th class="py-3 px-4">Catatan Anda</th>
                        <th class="py-3 px-4 text-right">Status Validasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentReports as $rep)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3 px-4 text-slate-500 whitespace-nowrap">{{ $rep->violation_date->format('d/m/Y') }}</td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="font-bold text-slate-900 block">{{ $rep->student->name }}</span>
                                <span class="text-[11px] text-slate-500">{{ $rep->student->schoolClass->name }}</span>
                            </td>
                            <td class="py-3 px-4 font-semibold text-slate-800">
                                {{ $rep->category->name }}
                                <span class="text-[11px] text-rose-600 font-bold block">+{{ $rep->category->points }} Poin</span>
                            </td>
                            <td class="py-3 px-4 max-w-xs text-slate-600">
                                "{{ $rep->notes ?? '-' }}"
                            </td>
                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                    {{ $rep->status === 'validated' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : '' }}
                                    {{ $rep->status === 'pending' ? 'bg-amber-50 text-amber-800 border border-amber-200' : '' }}
                                    {{ $rep->status === 'rejected' ? 'bg-rose-50 text-rose-800 border border-rose-200' : '' }}">
                                    {{ $rep->status === 'pending' ? 'Menunggu BK' : ($rep->status === 'validated' ? 'Disetujui' : 'Ditolak') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 font-medium">Anda belum pernah membuat laporan pelanggaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card List View (Clean & No Horizontal Scroll) -->
        <div class="sm:hidden divide-y divide-slate-100">
            @forelse($recentReports as $rep)
                <div class="p-4 space-y-2">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <span class="font-bold text-slate-900 text-sm block truncate">{{ $rep->student->name }}</span>
                            <span class="text-[11px] text-slate-500">{{ $rep->student->schoolClass->name }}</span>
                        </div>
                        <div class="flex flex-col items-end gap-1 flex-shrink-0">
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider
                                {{ $rep->status === 'validated' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : '' }}
                                {{ $rep->status === 'pending' ? 'bg-amber-50 text-amber-800 border border-amber-200' : '' }}
                                {{ $rep->status === 'rejected' ? 'bg-rose-50 text-rose-800 border border-rose-200' : '' }}">
                                {{ $rep->status === 'pending' ? 'Menunggu BK' : ($rep->status === 'validated' ? 'Disetujui' : 'Ditolak') }}
                            </span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $rep->violation_date->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100 text-xs">
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-semibold text-slate-800 truncate">{{ $rep->category->name }}</span>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold text-rose-600 bg-rose-50 border border-rose-100 flex-shrink-0">
                                +{{ $rep->category->points }} Poin
                            </span>
                        </div>
                        @if($rep->notes)
                            <p class="text-[11px] text-slate-500 italic mt-1">"{{ $rep->notes }}"</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-slate-400 text-xs font-medium">
                    Anda belum pernah membuat laporan pelanggaran.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
