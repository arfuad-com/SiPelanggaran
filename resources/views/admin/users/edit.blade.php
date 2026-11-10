@extends('layouts.app')

@section('title', 'Edit Pengguna')
@section('page_title', 'Perbarui Data Pengguna')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <div class="mb-5">
            <h3 class="text-base font-bold text-slate-800">Edit Akun: {{ $user->name }}</h3>
            <p class="text-xs text-slate-500">Perbarui informasi NIP, nama, atau hak akses pengguna.</p>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-4 p-3.5 bg-slate-50 rounded-xl border border-slate-200">
                <img 
                    src="{{ $user->avatar_url }}" 
                    alt="{{ $user->name }}" 
                    class="w-14 h-14 rounded-xl object-cover ring-2 ring-slate-200 shadow-xs flex-shrink-0"
                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0f172a&color=ffffff&size=128&bold=true'"
                >
                <div class="flex-1 min-w-0">
                    <label for="avatar" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Ganti Foto Profil (Opsional)
                    </label>
                    <input 
                        type="file" 
                        name="avatar" 
                        id="avatar" 
                        accept="image/*"
                        class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-200 file:text-slate-800 hover:file:bg-slate-300 cursor-pointer"
                    >
                    <p class="text-[10px] text-slate-400 mt-1">Format: JPG, JPEG, PNG, WEBP (Maks 2MB)</p>
                    @error('avatar')
                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="nip" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Nomor Induk Pegawai (NIP) <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="nip" 
                    id="nip" 
                    value="{{ old('nip', $user->nip) }}" 
                    required 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500"
                >
                @error('nip')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name', $user->name) }}" 
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
                        value="{{ old('email', $user->email) }}" 
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
                        value="{{ old('phone', $user->phone) }}" 
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
                        <option value="L" {{ old('gender', $user->gender) === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                        <option value="P" {{ old('gender', $user->gender) === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
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
                        value="{{ old('position', $user->position) }}" 
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
                >{{ old('domisili', $user->domisili) }}</textarea>
                @error('domisili')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="role" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Peran (Role) <span class="text-rose-500">*</span>
                    </label>
                    <select name="role" id="role" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="guru" {{ old('role', $user->role) === 'guru' ? 'selected' : '' }}>Guru Biasa</option>
                        <option value="bk" {{ old('role', $user->role) === 'bk' ? 'selected' : '' }}>Guru BK</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <div>
                    <label for="is_active" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                        Status Akun <span class="text-rose-500">*</span>
                    </label>
                    <select name="is_active" id="is_active" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="1" {{ old('is_active', $user->is_active) ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !old('is_active', $user->is_active) ? 'selected' : '' }}>Nonaktif (Blokir)</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-3">
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider transition shadow-lg shadow-indigo-600/30">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-xs uppercase tracking-wider transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
