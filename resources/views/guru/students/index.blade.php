@extends('layouts.app')

@section('title', 'Data Siswa')
@section('page_title', 'Direktori Data Siswa (Read Only)')

@section('content')
<div class="space-y-4 sm:space-y-5">
    <div class="bg-white p-4 sm:p-5 rounded-xl border border-slate-200">
        <h3 class="text-base sm:text-lg font-bold text-slate-900 leading-tight">Direktori Siswa</h3>
        <p class="text-xs text-slate-500 mt-0.5">Informasi profil dan catatan akumulasi pelanggaran siswa sekolah.</p>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white p-3.5 sm:p-4 rounded-xl border border-slate-200">
        <form method="GET" action="{{ route('guru.students.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-2.5">
            <div class="sm:col-span-6 relative">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari nama atau NISN..." 
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
                <a href="{{ route('guru.students.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-medium transition text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Student Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-4">
        @forelse($students as $s)
            <div class="bg-white rounded-xl p-4 border border-slate-200 hover:border-slate-300 transition-all flex flex-col justify-between shadow-xs">
                <div>
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
                        
                        <div class="text-right flex-shrink-0">
                            <span class="px-2.5 py-1 rounded-md text-xs font-extrabold inline-block
                                {{ $s->total_violation_points >= 50 ? 'bg-rose-50 text-rose-700 border border-rose-200' : ($s->total_violation_points > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-slate-50 text-slate-600 border border-slate-200') }}">
                                {{ $s->total_violation_points }} Poin
                            </span>
                        </div>
                    </div>

                    <div class="text-xs text-slate-600 space-y-1.5 my-3 bg-slate-50/80 p-2.5 rounded-lg border border-slate-100">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Jenis Kelamin:</span>
                            <span class="font-medium text-slate-700">{{ $s->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-between border-t border-slate-100 text-xs">
                    <span class="text-slate-400 font-medium">Status Perilaku</span>
                    <a href="{{ route('guru.students.show', $s->id) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-800 font-semibold rounded-lg transition">
                        Histori Pelanggaran &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-xl border border-slate-200 text-xs font-medium">
                Tidak ada data siswa ditemukan.
            </div>
        @endforelse
    </div>

    <div>
        {{ $students->links() }}
    </div>
</div>
@endsection
