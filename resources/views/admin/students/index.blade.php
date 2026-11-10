@extends('layouts.app')

@section('title', 'Master Data Siswa')
@section('page_title', 'Kelola Data Siswa')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Daftar Seluruh Siswa</h3>
            <p class="text-xs text-slate-500">Kelola informasi siswa beserta kelas dan akumulasi poin sanksi perilaku.</p>
        </div>
        <a href="{{ route('admin.students.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-indigo-600/30 transition uppercase tracking-wider">
            <span>+</span> Tambah Siswa Baru
        </a>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('admin.students.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari nama, NISN, atau NIS..." 
                    class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                >
            </div>
            <div>
                <select name="class_id" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-700 transition">
                    Filter
                </button>
                <a href="{{ route('admin.students.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Students List (Responsive: Cards for Mobile, Table for Desktop) -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">NISN / NIS</th>
                        <th class="py-3.5 px-4">Nama Siswa</th>
                        <th class="py-3.5 px-4">Kelas</th>
                        <th class="py-3.5 px-4">L/P</th>
                        <th class="py-3.5 px-4 text-center">Total Poin Sanksi</th>
                        <th class="py-3.5 px-4">Kontak Orang Tua</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $s)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-mono">
                                <span class="font-bold text-slate-800">{{ $s->nisn }}</span>
                                <span class="block text-[11px] text-slate-400">NIS: {{ $s->nis ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-800">
                                <a href="{{ route('admin.students.show', $s->id) }}" class="text-slate-900 hover:text-indigo-600 hover:underline transition">
                                    {{ $s->name }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 bg-slate-100 rounded-md font-semibold text-slate-700">
                                    {{ $s->schoolClass->name }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold {{ $s->gender === 'L' ? 'text-blue-600' : 'text-rose-600' }}">
                                {{ $s->gender }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-lg font-bold text-xs
                                    {{ $s->total_violation_points >= 50 ? 'bg-rose-100 text-rose-700 border border-rose-200' : ($s->total_violation_points > 0 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-50 text-emerald-700') }}">
                                    {{ $s->total_violation_points }} Poin
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-500">
                                <p class="text-slate-700 font-medium">{{ $s->parent_name ?? '-' }}</p>
                                <p class="text-[11px] font-mono">{{ $s->parent_phone ?? '-' }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-1.5 whitespace-nowrap">
                                <a href="{{ route('admin.students.show', $s->id) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white/80 hover:bg-slate-50 text-slate-600 hover:text-indigo-600 border border-slate-200 rounded-lg text-[11px] font-semibold transition shadow-2xs" title="Lihat Rekam Jejak">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Detail</span>
                                </a>
                                <a href="{{ route('admin.students.edit', $s->id) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white/80 hover:bg-slate-50 text-slate-600 hover:text-slate-900 border border-slate-200 rounded-lg text-[11px] font-semibold transition shadow-2xs" title="Edit Data">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    <span>Edit</span>
                                </a>
                                <form method="POST" action="{{ route('admin.students.destroy', $s->id) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus siswa ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white/80 hover:bg-rose-50 text-slate-500 hover:text-rose-600 border border-slate-200 rounded-lg text-[11px] font-semibold transition shadow-2xs" title="Hapus">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">Tidak ada siswa ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card List View (Clean & No Horizontal Scroll) -->
        <div class="md:hidden divide-y divide-slate-100">
            @forelse($students as $s)
                <div class="p-4 space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                                    {{ $s->schoolClass->name }}
                                </span>
                                <span class="text-[10px] font-bold {{ $s->gender === 'L' ? 'text-blue-600 bg-blue-50' : 'text-rose-600 bg-rose-50' }} px-1.5 py-0.5 rounded">
                                    {{ $s->gender === 'L' ? 'L' : 'P' }}
                                </span>
                            </div>
                            <a href="{{ route('admin.students.show', $s->id) }}" class="font-bold text-slate-900 text-sm hover:text-indigo-600 block mt-1 truncate">
                                {{ $s->name }}
                            </a>
                            <p class="text-[11px] text-slate-400 font-mono mt-0.5">NISN: {{ $s->nisn }} • NIS: {{ $s->nis ?? '-' }}</p>
                        </div>

                        <div class="text-right flex-shrink-0">
                            <span class="px-2.5 py-1 rounded-xl font-black text-xs inline-block
                                {{ $s->total_violation_points >= 50 ? 'bg-rose-100 text-rose-700 border border-rose-200' : ($s->total_violation_points > 0 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-50 text-emerald-700') }}">
                                {{ $s->total_violation_points }} Poin
                            </span>
                        </div>
                    </div>

                    <!-- Kontak Ortu -->
                    <div class="bg-slate-50/80 rounded-xl p-2.5 text-xs text-slate-600 space-y-1 border border-slate-100">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">Wali:</span>
                            <span class="font-medium text-slate-800 truncate max-w-[170px]">{{ $s->parent_name ?? '-' }}</span>
                        </div>
                        @if($s->parent_phone)
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">No. Kontak:</span>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $s->parent_phone) }}" target="_blank" class="font-mono font-bold text-emerald-600 hover:underline">
                                    📞 {{ $s->parent_phone }}
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Action buttons touch-friendly (Subtle, Transparent, Clean SVGs) -->
                    <div class="grid grid-cols-3 gap-2 pt-1">
                        <a href="{{ route('admin.students.show', $s->id) }}" class="py-2 text-center bg-white/70 hover:bg-slate-50 active:scale-95 border border-slate-200/90 text-slate-600 hover:text-indigo-600 rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 shadow-2xs">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>Detail</span>
                        </a>
                        <a href="{{ route('admin.students.edit', $s->id) }}" class="py-2 text-center bg-white/70 hover:bg-slate-50 active:scale-95 border border-slate-200/90 text-slate-600 hover:text-slate-900 rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 shadow-2xs">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            <span>Edit</span>
                        </a>
                        <form method="POST" action="{{ route('admin.students.destroy', $s->id) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus siswa ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full py-2 text-center bg-white/70 hover:bg-rose-50 active:scale-95 border border-slate-200/90 text-slate-500 hover:text-rose-600 rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 shadow-2xs">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                <span>Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 text-xs">
                    Tidak ada siswa ditemukan.
                </div>
            @endforelse
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection
