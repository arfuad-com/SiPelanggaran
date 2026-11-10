@extends('layouts.app')

@section('title', 'Edit Siswa')
@section('page_title', 'Perbarui Data Siswa')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <div class="mb-5">
            <h3 class="text-base font-bold text-slate-800">Edit Data: {{ $student->name }}</h3>
            <p class="text-xs text-slate-500">Perbarui profil siswa (total poin saat ini: {{ $student->total_violation_points }} poin).</p>
        </div>

        <form method="POST" action="{{ route('admin.students.update', $student->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="nisn" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        NISN <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="nisn" 
                        id="nisn" 
                        value="{{ old('nisn', $student->nisn) }}" 
                        required 
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500"
                    >
                    @error('nisn')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nis" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        NIS Lokal
                    </label>
                    <input 
                        type="text" 
                        name="nis" 
                        id="nis" 
                        value="{{ old('nis', $student->nis) }}" 
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500"
                    >
                </div>
            </div>

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Nama Lengkap Siswa <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name', $student->name) }}" 
                    required 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500"
                >
                @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="school_class_id" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Kelas <span class="text-rose-500">*</span>
                    </label>
                    <select name="school_class_id" id="school_class_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500">
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ old('school_class_id', $student->school_class_id) == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="gender" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Jenis Kelamin <span class="text-rose-500">*</span>
                    </label>
                    <select name="gender" id="gender" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="L" {{ old('gender', $student->gender) === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                        <option value="P" {{ old('gender', $student->gender) === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="parent_name" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Nama Orang Tua / Wali
                    </label>
                    <input 
                        type="text" 
                        name="parent_name" 
                        id="parent_name" 
                        value="{{ old('parent_name', $student->parent_name) }}" 
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500"
                    >
                </div>

                <div>
                    <label for="parent_phone" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        No. HP / WhatsApp Orang Tua
                    </label>
                    <input 
                        type="text" 
                        name="parent_phone" 
                        id="parent_phone" 
                        value="{{ old('parent_phone', $student->parent_phone) }}" 
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500"
                    >
                </div>
            </div>

            <div class="flex items-center gap-3 pt-3">
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider transition shadow-lg shadow-indigo-600/30">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.students.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-xs uppercase tracking-wider transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
