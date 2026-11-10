@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')
@section('page_title', 'Form Pembuatan Akun Baru')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <div class="mb-5">
            <h3 class="text-base font-bold text-slate-800">Tambah Akun Pegawai / Guru</h3>
            <p class="text-xs text-slate-500">Akun baru akan otomatis memiliki kata sandi default: <code class="font-mono text-indigo-600 bg-indigo-50 px-1 py-0.5 rounded">password123</code></p>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label for="avatar" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Foto Profil Pegawai (Opsional)
                </label>
                <input 
                    type="file" 
                    name="avatar" 
                    id="avatar" 
                    accept="image/*"
                    class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500 bg-slate-50"
                >
                <p class="text-[11px] text-slate-400 mt-1">Format: JPG, JPEG, PNG, WEBP (Maks 2MB). Jika dikosongkan, avatar akan dibuat otomatis berdasarkan peran.</p>
                @error('avatar')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nip" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Nomor Induk Pegawai (NIP) <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="nip" 
                    id="nip" 
                    value="{{ old('nip') }}" 
                    placeholder="Contoh: 198805052014031003" 
                    required 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500"
                >
                @error('nip')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Nama Lengkap Beserta Gelar <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}" 
                    placeholder="Contoh: Agus Salim, S.Pd" 
                    required 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500"
                >
                @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Alamat Email (Opsional)
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}" 
                        placeholder="Contoh: agus@sekolah.sch.id" 
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500"
                    >
                    @error('email')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Nomor HP / WhatsApp
                    </label>
                    <input 
                        type="text" 
                        name="phone" 
                        id="phone" 
                        value="{{ old('phone') }}" 
                        placeholder="Contoh: 081234567890" 
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 font-mono"
                    >
                    @error('phone')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="gender" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Jenis Kelamin
                    </label>
                    <select name="gender" id="gender" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                        <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                    </select>
                    @error('gender')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="position" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Jabatan / Tugas Mengajar
                    </label>
                    <input 
                        type="text" 
                        name="position" 
                        id="position" 
                        value="{{ old('position') }}" 
                        placeholder="Contoh: Wali Kelas X RPL 1" 
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500"
                    >
                    @error('position')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="domisili" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Alamat Domisili Tempat Tinggal
                </label>
                <textarea 
                    name="domisili" 
                    id="domisili" 
                    rows="2" 
                    placeholder="Contoh: Jl. Soekarno-Hatta No. 240, Kel. Batununggal, Kota Bandung"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500"
                >{{ old('domisili') }}</textarea>
                @error('domisili')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="role" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Peran / Hak Akses (Role) <span class="text-rose-500">*</span>
                </label>
                <select name="role" id="role" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Pilih Peran --</option>
                    <option value="guru" {{ old('role') === 'guru' ? 'selected' : '' }}>Guru Biasa (Wali Kelas / Guru Mapel - Pelapor)</option>
                    <option value="bk" {{ old('role') === 'bk' ? 'selected' : '' }}>Guru BK (Validator & Konselor)</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin (Super User Pengelola Sistem)</option>
                </select>
                @error('role')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-3">
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider transition shadow-lg shadow-indigo-600/30">
                    Simpan Akun
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-xs uppercase tracking-wider transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
