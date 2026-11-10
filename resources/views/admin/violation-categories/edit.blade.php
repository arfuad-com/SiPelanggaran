@extends('layouts.app')

@section('title', 'Edit Kategori Pelanggaran')
@section('page_title', 'Perbarui Kategori Pelanggaran')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <div class="mb-5">
            <h3 class="text-base font-bold text-slate-800">Edit Kategori: {{ $violationCategory->name }}</h3>
            <p class="text-xs text-slate-500">Perbarui nama, bobot poin, atau deskripsi pelanggaran.</p>
        </div>

        <form method="POST" action="{{ route('admin.violation-categories.update', $violationCategory->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Nama / Judul Pelanggaran <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name', $violationCategory->name) }}" 
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
                    value="{{ old('points', $violationCategory->points) }}" 
                    min="1" 
                    max="500" 
                    required 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500"
                >
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
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500"
                >{{ old('description', $violationCategory->description) }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-3">
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider transition shadow-lg shadow-indigo-600/30">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.violation-categories.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-xs uppercase tracking-wider transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
