@extends('layouts.app')

@section('title', 'Tambah Kategori Pelanggaran')
@section('page_title', 'Form Kategori Pelanggaran')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <div class="mb-5">
            <h3 class="text-base font-bold text-slate-800">Tambah Jenis & Bobot Pelanggaran</h3>
            <p class="text-xs text-slate-500">Tentukan bobot poin sanksi yang adil sesuai tata tertib sekolah.</p>
        </div>

        <form method="POST" action="{{ route('admin.violation-categories.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Nama / Judul Pelanggaran <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}" 
                    placeholder="Contoh: Menggunakan HP saat jam pelajaran tanpa izin" 
                    required 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500"
                >
                @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="points" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Bobot Poin Sanksi (Angka) <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="number" 
                    name="points" 
                    id="points" 
                    value="{{ old('points', 10) }}" 
                    min="1" 
                    max="500" 
                    required 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500"
                >
                <p class="text-[11px] text-slate-400 mt-1">Contoh: Ringan (5-15), Sedang (20-40), Berat (50-100+)</p>
                @error('points')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Keterangan / Batasan Pelanggaran
                </label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="3" 
                    placeholder="Penjelasan detail indikator tindakan yang melanggar..." 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500"
                >{{ old('description') }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-3">
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider transition shadow-lg shadow-indigo-600/30">
                    Simpan Kategori
                </button>
                <a href="{{ route('admin.violation-categories.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-xs uppercase tracking-wider transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
