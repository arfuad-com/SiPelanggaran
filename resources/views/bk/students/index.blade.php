@extends('layouts.app')

@section('title', 'Data & Rekam Jejak Siswa')
@section('page_title', 'Direktori Rekam Jejak Siswa')

@section('content')
<div class="space-y-4 sm:space-y-5">

    <!-- Header & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-xl border border-slate-200">
        <div>
            <h3 class="text-base sm:text-lg font-bold text-slate-900 leading-tight">Rekam Jejak Siswa</h3>
            <p class="text-xs text-slate-500 mt-0.5">Pantau akumulasi poin sanksi perilaku dan riwayat bimbingan konseling.</p>
        </div>
        <a href="{{ route('bk.violations.create') }}" class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold text-xs rounded-lg transition shadow-xs self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path>
            </svg>
            <span>Catat Kasus Pelanggaran</span>
        </a>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white p-3.5 sm:p-4 rounded-xl border border-slate-200">
        <form method="GET" action="{{ route('bk.students.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-2.5">
            <div class="sm:col-span-6 relative">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari nama, NISN, atau NIS..." 
                    class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 placeholder:text-slate-400"
                >
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                </svg>
            </div>
            
            <div class="sm:col-span-4">
                <select name="class_id" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="sm:col-span-2 flex gap-1.5">
                <button type="submit" class="flex-1 py-2 bg-slate-800 hover:bg-slate-900 active:scale-95 text-white rounded-lg text-xs font-semibold transition text-center">
                    Cari
                </button>
                <a href="{{ route('bk.students.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-medium transition text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Student Cards Grid (Comfortable on both Mobile and Desktop) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-4">
        @forelse($students as $s)
            <div class="bg-white rounded-xl p-4 border border-slate-200 hover:border-slate-300 transition-all flex flex-col justify-between shadow-xs">
                <div>
                    <!-- Card Header -->
                    <div class="flex items-start justify-between gap-3 pb-3 border-b border-slate-100">
                        <div class="min-w-0 flex-1">
                            <span class="inline-block text-[10px] font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200/60">
                                {{ $s->schoolClass->name }}
                            </span>
                            <h4 class="font-bold text-slate-900 text-sm mt-1.5 truncate">
                                {{ $s->name }}
                            </h4>
                            <p class="text-[11px] text-slate-500 font-mono mt-0.5">NISN: {{ $s->nisn }}</p>
                        </div>
                        
                        <!-- Point Badge -->
                        <div class="text-right flex-shrink-0">
                            <span class="px-2.5 py-1 rounded-md text-xs font-extrabold inline-block
                                {{ $s->total_violation_points >= 50 ? 'bg-rose-50 text-rose-700 border border-rose-200' : ($s->total_violation_points > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-slate-50 text-slate-600 border border-slate-200') }}">
                                {{ $s->total_violation_points }} Poin
                            </span>
                            @if($s->total_violation_points >= 50)
                                <span class="text-[10px] text-rose-600 font-bold block mt-1">Panggilan Ortu</span>
                            @endif
                        </div>
                    </div>

                    <!-- Meta Details -->
                    <div class="text-xs text-slate-600 space-y-1.5 my-3 bg-slate-50/80 p-2.5 rounded-lg border border-slate-100">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Jenis Kelamin:</span>
                            <span class="font-medium text-slate-700">{{ $s->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Wali Murid:</span>
                            <span class="font-medium text-slate-700 truncate max-w-[150px]">{{ $s->parent_name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Kontak:</span>
                            <span class="font-mono font-medium text-slate-700">{{ $s->parent_phone ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card Actions -->
                <div class="pt-2.5 flex items-center justify-between gap-2 border-t border-slate-100 text-xs">
                    <a href="{{ route('bk.counseling.create', ['student_id' => $s->id]) }}" class="font-semibold text-emerald-700 hover:text-emerald-800 hover:underline">
                        + Konseling
                    </a>
                    <a href="{{ route('bk.students.show', $s->id) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-800 font-semibold rounded-lg transition">
                        Buka Rekam Jejak &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-xl border border-slate-200 text-xs font-medium">
                Tidak ada data siswa yang cocok dengan kriteria pencarian.
            </div>
        @endforelse
    </div>

    <div class="pt-2">
        {{ $students->links() }}
    </div>
</div>
@endsection
