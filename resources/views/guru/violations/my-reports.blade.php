@extends('layouts.app')

@section('title', 'Riwayat Laporan Saya')
@section('page_title', 'Riwayat Laporan Pelanggaran yang Pernah Dibuat')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h3 class="text-lg sm:text-xl font-extrabold text-slate-900">Daftar Laporan Anda</h3>
            <p class="text-xs text-slate-500">Pantau proses tindak lanjut verifikasi Guru BK secara langsung.</p>
        </div>
        <a href="{{ route('guru.violations.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-indigo-600/30 transition-all self-start sm:self-auto">
            <span>+</span> Buat Laporan Baru
        </a>
    </div>

    <!-- Filter Status Tabs (Scrollable on mobile) -->
    <div class="bg-white p-3 rounded-2xl shadow-soft border border-slate-200/70 overflow-x-auto">
        <div class="flex items-center gap-2 min-w-max text-xs">
            <span class="font-bold text-slate-400 mr-2 text-[11px] uppercase">Filter:</span>
            
            <a href="{{ route('guru.violations.my-reports') }}" class="px-3.5 py-1.5 rounded-xl font-bold transition-all {{ !request('status') ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua
            </a>
            
            <a href="{{ route('guru.violations.my-reports', ['status' => 'pending']) }}" class="px-3.5 py-1.5 rounded-xl font-bold transition-all {{ request('status') === 'pending' ? 'bg-amber-500 text-slate-950 shadow-xs' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                Menunggu Validasi (Pending)
            </a>
            
            <a href="{{ route('guru.violations.my-reports', ['status' => 'validated']) }}" class="px-3.5 py-1.5 rounded-xl font-bold transition-all {{ request('status') === 'validated' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                Disetujui BK (Validated)
            </a>
            
            <a href="{{ route('guru.violations.my-reports', ['status' => 'rejected']) }}" class="px-3.5 py-1.5 rounded-xl font-bold transition-all {{ request('status') === 'rejected' ? 'bg-rose-600 text-white shadow-xs' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}">
                Ditolak BK
            </a>
        </div>
    </div>

    <!-- Reports Table Card -->
    <div class="bg-white rounded-3xl shadow-soft border border-slate-200/70 overflow-hidden">
        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">Tanggal Kejadian</th>
                        <th class="py-3.5 px-4">Nama Siswa & Kelas</th>
                        <th class="py-3.5 px-4">Jenis Pelanggaran</th>
                        <th class="py-3.5 px-4 text-center">Bobot Poin</th>
                        <th class="py-3.5 px-4">Kronologi Anda</th>
                        <th class="py-3.5 px-4 text-center">Status Laporan</th>
                        <th class="py-3.5 px-4">Verifikator BK</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reports as $r)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap">
                                <span class="font-bold text-slate-800 block">{{ $r->violation_date->format('d/m/Y') }}</span>
                                <span class="text-[11px] text-slate-400">Jam: {{ $r->created_at->format('H:i') }} WIB</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <a href="{{ route('guru.students.show', $r->student->id) }}" class="font-bold text-indigo-600 hover:underline block text-xs sm:text-sm">
                                    {{ $r->student->name }}
                                </a>
                                <span class="text-[11px] text-slate-400">{{ $r->student->schoolClass->name }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-800">
                                {{ $r->category->name }}
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 font-extrabold text-xs">
                                    +{{ $r->category->points }} Poin
                                </span>
                            </td>
                            <td class="py-3.5 px-4 max-w-xs text-slate-600 italic">
                                "{{ $r->notes ?? '-' }}"
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $r->status === 'validated' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                    {{ $r->status === 'pending' ? 'bg-amber-50 text-amber-700 border border-amber-200 animate-pulse' : '' }}
                                    {{ $r->status === 'rejected' ? 'bg-rose-50 text-rose-700 border border-rose-200' : '' }}">
                                    {{ $r->status === 'pending' ? '⏳ Menunggu BK' : ($r->status === 'validated' ? '✓ Disetujui' : '✕ Ditolak') }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 whitespace-nowrap">
                                @if($r->validator)
                                    <span class="font-bold text-slate-800 block">{{ $r->validator->name }}</span>
                                    <span class="text-[10px] text-slate-400 font-mono">{{ $r->validated_at?->format('d/m/Y H:i') }}</span>
                                @else
                                    <span class="text-slate-400 italic">Dalam antrean</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-slate-400 font-medium">
                                Tidak ada laporan yang ditemukan untuk kriteria ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card List View (Clean & No Horizontal Scroll) -->
        <div class="md:hidden divide-y divide-slate-100">
            @forelse($reports as $r)
                <div class="p-4 space-y-2.5">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <span class="text-[10px] text-slate-400 font-mono">{{ $r->violation_date->format('d/m/Y') }} • {{ $r->created_at->format('H:i') }} WIB</span>
                            <a href="{{ route('guru.students.show', $r->student->id) }}" class="font-bold text-slate-900 text-sm hover:text-indigo-600 block mt-0.5 truncate">
                                {{ $r->student->name }}
                            </a>
                            <span class="text-[11px] text-slate-500">{{ $r->student->schoolClass->name }}</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 font-black text-xs flex-shrink-0">
                            +{{ $r->category->points }} Poin
                        </span>
                    </div>

                    <div class="bg-slate-50/80 rounded-xl p-3 border border-slate-100 text-xs space-y-1.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-800 truncate">{{ $r->category->name }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider flex-shrink-0
                                {{ $r->status === 'validated' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                {{ $r->status === 'pending' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                                {{ $r->status === 'rejected' ? 'bg-rose-50 text-rose-700 border border-rose-200' : '' }}">
                                {{ $r->status === 'pending' ? '⏳ Menunggu BK' : ($r->status === 'validated' ? '✓ Disetujui' : '✕ Ditolak') }}
                            </span>
                        </div>
                        @if($r->notes)
                            <p class="text-[11px] text-slate-600 italic pt-0.5">"{{ $r->notes }}"</p>
                        @endif
                        @if($r->validator)
                            <div class="pt-1.5 border-t border-slate-200/60 flex items-center justify-between text-[11px] text-slate-500">
                                <span>Verifikator BK:</span>
                                <span class="font-semibold text-slate-800">{{ $r->validator->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 text-xs">
                    Tidak ada laporan yang ditemukan untuk kriteria ini.
                </div>
            @endforelse
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection
