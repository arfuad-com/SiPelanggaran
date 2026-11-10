@extends('layouts.app')

@section('title', 'Kategori Pelanggaran')
@section('page_title', 'Master Kategori & Bobot Poin Pelanggaran')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Daftar Kategori Pelanggaran</h3>
            <p class="text-xs text-slate-500">Bobot poin sanksi akan otomatis diakumulasikan ke siswa ketika laporan divalidasi oleh Guru BK.</p>
        </div>
        <a href="{{ route('admin.violation-categories.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-indigo-600/30 transition uppercase tracking-wider">
            <span>+</span> Tambah Kategori Baru
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <!-- Desktop Table View -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">Nama Pelanggaran</th>
                        <th class="py-3.5 px-4 text-center">Bobot Poin</th>
                        <th class="py-3.5 px-4">Deskripsi / Ketentuan</th>
                        <th class="py-3.5 px-4 text-center">Pernah Terjadi</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($categories as $cat)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-800 text-sm">
                                {{ $cat->name }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-3 py-1 rounded-lg font-black text-xs
                                    {{ $cat->points >= 50 ? 'bg-rose-100 text-rose-700 border border-rose-200' : ($cat->points >= 20 ? 'bg-amber-100 text-amber-700' : 'bg-blue-50 text-blue-700') }}">
                                    +{{ $cat->points }} Poin
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 max-w-md">
                                {{ $cat->description ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2 py-0.5 rounded-full bg-slate-100 font-semibold text-slate-600">
                                    {{ $cat->violations_count }} Kali
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-1.5 whitespace-nowrap">
                                <a href="{{ route('admin.violation-categories.edit', $cat->id) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white/80 hover:bg-slate-50 text-slate-600 hover:text-slate-900 border border-slate-200 rounded-lg text-[11px] font-semibold transition shadow-2xs">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    <span>Edit</span>
                                </a>
                                <form method="POST" action="{{ route('admin.violation-categories.destroy', $cat->id) }}" class="inline" onsubmit="return confirm('Hapus kategori pelanggaran ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white/80 hover:bg-rose-50 text-slate-500 hover:text-rose-600 border border-slate-200 rounded-lg text-[11px] font-semibold transition shadow-2xs">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">Belum ada kategori pelanggaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card List View -->
        <div class="sm:hidden divide-y divide-slate-100">
            @forelse($categories as $cat)
                <div class="p-4 space-y-2.5">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <h4 class="font-bold text-slate-900 text-sm leading-tight">{{ $cat->name }}</h4>
                            <span class="text-[10px] text-slate-400 block mt-0.5">{{ $cat->violations_count }} kali tercatat</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-xl font-black text-xs flex-shrink-0
                            {{ $cat->points >= 50 ? 'bg-rose-100 text-rose-700 border border-rose-200' : ($cat->points >= 20 ? 'bg-amber-100 text-amber-700' : 'bg-blue-50 text-blue-700') }}">
                            +{{ $cat->points }} Poin
                        </span>
                    </div>

                    @if($cat->description)
                        <p class="text-xs text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                            {{ $cat->description }}
                        </p>
                    @endif

                    <div class="flex items-center justify-end gap-2 pt-1">
                        <a href="{{ route('admin.violation-categories.edit', $cat->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/80 hover:bg-slate-50 border border-slate-200 text-slate-600 hover:text-slate-900 rounded-xl text-xs font-semibold transition shadow-2xs">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            <span>Edit</span>
                        </a>
                        <form method="POST" action="{{ route('admin.violation-categories.destroy', $cat->id) }}" onsubmit="return confirm('Hapus kategori pelanggaran ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-transparent hover:bg-rose-50/70 border border-slate-200 hover:border-rose-200 text-slate-500 hover:text-rose-600 rounded-xl text-xs font-medium transition">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                <span>Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 text-xs">Belum ada kategori pelanggaran.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
