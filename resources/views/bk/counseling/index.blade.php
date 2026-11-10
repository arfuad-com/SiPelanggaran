@extends('layouts.app')

@section('title', 'Bimbingan Konseling')
@section('page_title', 'Riwayat Sesi Bimbingan & Konseling Siswa')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Daftar Sesi Pembinaan & Konseling</h3>
            <p class="text-xs text-slate-500">Pencatatan resmi sesi konseling, tindak lanjut evaluasi psikososial & perilaku siswa.</p>
        </div>
        <a href="{{ route('bk.counseling.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-600/30 transition uppercase tracking-wider">
            <span>+</span> Catat Sesi Konseling Baru
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">Tanggal Konseling</th>
                        <th class="py-3.5 px-4">Nama Siswa & Kelas</th>
                        <th class="py-3.5 px-4">Guru Konselor</th>
                        <th class="py-3.5 px-4">Pokok Permasalahan</th>
                        <th class="py-3.5 px-4">Hasil Pembinaan</th>
                        <th class="py-3.5 px-4">Rencana Tindak Lanjut</th>
                        <th class="py-3.5 px-4 text-right">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($counselings as $c)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-700 whitespace-nowrap">
                                {{ $c->counseling_date->format('d/m/Y') }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <a href="{{ route('bk.students.show', $c->student->id) }}" class="font-bold text-indigo-600 hover:underline block text-sm">
                                    {{ $c->student->name }}
                                </a>
                                <span class="text-[11px] text-slate-400">{{ $c->student->schoolClass->name }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-700 whitespace-nowrap">
                                {{ $c->counselor->name }}
                            </td>
                            <td class="py-3.5 px-4 max-w-xs text-slate-700">
                                <p class="line-clamp-2">{{ $c->issue_summary }}</p>
                            </td>
                            <td class="py-3.5 px-4 max-w-xs text-slate-600">
                                <p class="line-clamp-2">{{ $c->counseling_result }}</p>
                            </td>
                            <td class="py-3.5 px-4 max-w-xs text-emerald-700 font-medium">
                                <p class="line-clamp-2">{{ $c->follow_up_action }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <a href="{{ route('bk.counseling.show', $c->id) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition">
                                    Lihat &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">Belum ada riwayat konseling tercatat.</td>
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
                            <a href="{{ route('bk.students.show', $c->student->id) }}" class="font-bold text-slate-900 text-sm hover:text-indigo-600 block truncate mt-0.5">
                                {{ $c->student->name }}
                            </a>
                            <span class="text-[11px] text-slate-500">{{ $c->student->schoolClass->name }}</span>
                        </div>
                        <a href="{{ route('bk.counseling.show', $c->id) }}" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl text-xs font-bold transition flex-shrink-0">
                            Buka &rarr;
                        </a>
                    </div>

                    <div class="bg-slate-50/80 rounded-xl p-3 border border-slate-100 text-xs space-y-1.5">
                        <div class="flex items-center justify-between text-[11px] text-slate-500">
                            <span>Konselor:</span>
                            <span class="font-semibold text-slate-800">{{ $c->counselor->name }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Permasalahan:</span>
                            <p class="text-slate-800 mt-0.5 font-medium">{{ $c->issue_summary }}</p>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Hasil Pembinaan:</span>
                            <p class="text-slate-700 mt-0.5">{{ $c->counseling_result }}</p>
                        </div>
                        @if($c->follow_up_action)
                            <div class="pt-1 border-t border-slate-200/60">
                                <span class="text-[10px] font-bold text-emerald-700 uppercase block">Tindak Lanjut:</span>
                                <p class="text-emerald-900 font-medium mt-0.5">{{ $c->follow_up_action }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 text-xs">Belum ada riwayat konseling tercatat.</div>
            @endforelse
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $counselings->links() }}
        </div>
    </div>
</div>
@endsection
