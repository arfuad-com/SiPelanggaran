@extends('layouts.app')

@section('title', 'Profil & Kata Sandi')
@section('page_title', 'Pengaturan Profil Pengguna')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 text-sm font-bold">
                    👤
                </span>
                <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">Profil & Pengaturan Akun</h3>
            </div>
            <p class="text-xs sm:text-sm text-slate-500">
                Kelola identitas akun, kontak aktif, foto profil, dan kata sandi masuk aplikasi Anda.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Status: Aktif
            </span>
        </div>
    </div>

    <!-- Main Grid: Left Profile Card, Right Form Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- ================= LEFT COLUMN: Profile Overview ================= -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Profile Identity Card -->
            <div class="bg-white rounded-2xl p-6 shadow-xs border border-slate-200/80 text-center">
                <div class="relative inline-block mx-auto mb-4 group">
                    <img 
                        id="avatar-preview"
                        src="{{ $user->avatar_url }}" 
                        alt="{{ $user->name }}" 
                        class="w-28 h-28 rounded-2xl object-cover ring-4 ring-slate-100 shadow-md border border-slate-200 mx-auto"
                        onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0f172a&color=ffffff&size=128&bold=true'"
                    >
                    <div class="absolute -bottom-2 -right-2">
                        <label for="avatar_input" class="w-8 h-8 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white flex items-center justify-center cursor-pointer shadow-md transition active:scale-95" title="Ganti Foto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </label>
                    </div>
                </div>

                <form id="avatar_form" method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data" class="hidden">
                    @csrf
                    @method('PUT')
                    <input type="file" id="avatar_input" name="avatar" accept="image/*" onchange="previewAvatar(this); document.getElementById('avatar_form').submit();">
                </form>

                <h4 class="text-base sm:text-lg font-extrabold text-slate-900 leading-snug">{{ $user->name }}</h4>
                <p class="text-xs text-slate-500 font-mono mt-0.5">NIP: {{ $user->nip }}</p>

                <div class="mt-3 flex items-center justify-center gap-1.5">
                    <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-lg uppercase tracking-wider
                        {{ $user->role === 'admin' ? 'bg-red-50 text-red-700 border border-red-200' : '' }}
                        {{ $user->role === 'bk' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : '' }}
                        {{ $user->role === 'guru' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}">
                        {{ $user->role === 'bk' ? 'Konselor BK' : ($user->role === 'guru' ? 'Guru / Wali Kelas' : 'Administrator') }}
                    </span>
                </div>

                <label for="avatar_input" class="mt-4 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 text-xs font-semibold cursor-pointer transition">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Upload Foto Baru</span>
                </label>
                <p class="text-[10px] text-slate-400 mt-1">JPG, PNG, atau WEBP maks 2MB</p>
                @error('avatar')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror

                <!-- Quick Detail Summary -->
                <div class="mt-5 pt-5 border-t border-slate-100 text-left space-y-3 text-xs">
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-slate-400">Jabatan:</span>
                        <span class="font-semibold text-slate-800 text-right">{{ $user->position ?? '-' }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-slate-400">Email:</span>
                        <span class="font-semibold text-slate-800 text-right truncate max-w-[170px]">{{ $user->email ?? '-' }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-slate-400">No. WhatsApp:</span>
                        <span class="font-mono font-semibold text-slate-800 text-right">{{ $user->phone ?? '-' }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-slate-400">Domisili:</span>
                        <span class="font-semibold text-slate-800 text-right leading-tight max-w-[170px]">{{ $user->domisili ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Role Badge Information -->
            <div class="bg-indigo-50/70 border border-indigo-100 rounded-2xl p-5 text-xs text-indigo-900 space-y-2">
                <div class="flex items-center gap-2 font-bold text-indigo-950">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Hak Akses Anda</span>
                </div>
                <p class="text-indigo-800 leading-relaxed text-[11px]">
                    @if($user->isBK())
                        Sebagai <strong>Konselor BK</strong>, Anda berwenang memvalidasi laporan pelanggaran guru, menginput kasus langsung, serta menyelenggarakan bimbingan konseling siswa.
                    @elseif($user->isGuru())
                        Sebagai <strong>Guru</strong>, Anda berwenang melaporkan insiden pelanggaran siswa dan memantau status validasi serta rekam jejak perilaku siswa.
                    @else
                        Sebagai <strong>Administrator</strong>, Anda memiliki kendali penuh atas manajemen pengguna, master kategori sanksi, dan laporan berkala.
                    @endif
                </p>
            </div>
        </div>

        <!-- ================= RIGHT COLUMN: Form Controls ================= -->
        <div class="lg:col-span-8 space-y-6">

            <!-- Card 1: Biodata Form -->
            <div class="bg-white rounded-2xl p-6 shadow-xs border border-slate-200/80">
                <div class="mb-5 pb-4 border-b border-slate-100">
                    <h4 class="text-base font-bold text-slate-900">Perbarui Biodata & Alamat Domisili</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Lengkapi identitas pribadi, kontak aktif, dan alamat tempat tinggal resmi Anda.</p>
                </div>

                <form method="POST" action="{{ route('profile.information.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Nama Lengkap & Gelar <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                value="{{ old('name', $user->name) }}" 
                                required 
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm transition"
                            >
                            @error('name')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Alamat Email
                            </label>
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                value="{{ old('email', $user->email) }}" 
                                placeholder="email@sekolah.sch.id"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm transition"
                            >
                            @error('email')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                No. HP / WhatsApp
                            </label>
                            <input 
                                type="text" 
                                name="phone" 
                                id="phone" 
                                value="{{ old('phone', $user->phone) }}" 
                                placeholder="Contoh: 081234567890" 
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm font-mono transition"
                            >
                            @error('phone')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="gender" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Jenis Kelamin
                            </label>
                            <select name="gender" id="gender" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm transition">
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('gender', $user->gender) === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                                <option value="P" {{ old('gender', $user->gender) === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                            </select>
                            @error('gender')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="position" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Jabatan / Tugas
                            </label>
                            <input 
                                type="text" 
                                name="position" 
                                id="position" 
                                value="{{ old('position', $user->position) }}" 
                                placeholder="Contoh: Koordinator BK" 
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm transition"
                            >
                            @error('position')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="domisili" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Alamat Domisili Tempat Tinggal
                        </label>
                        <textarea 
                            name="domisili" 
                            id="domisili" 
                            rows="2" 
                            placeholder="Contoh: Jl. Soekarno-Hatta No. 240, Kel. Batununggal, Kota Bandung"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm transition"
                        >{{ old('domisili', $user->domisili) }}</textarea>
                        @error('domisili')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-bold rounded-xl text-xs uppercase tracking-wider transition shadow-xs flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Simpan Biodata</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card 2: Password Change Form -->
            <div class="bg-white rounded-2xl p-6 shadow-xs border border-slate-200/80">
                <div class="mb-5 pb-4 border-b border-slate-100">
                    <h4 class="text-base font-bold text-slate-900">Perbarui Kata Sandi</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Gunakan kombinasi minimal 8 karakter dengan huruf dan angka untuk proteksi optimal.</p>
                </div>

                <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            name="current_password" 
                            id="current_password" 
                            required 
                            placeholder="Masukkan kata sandi lama Anda"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm transition"
                        >
                        @error('current_password')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Kata Sandi Baru <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                required 
                                placeholder="Minimal 8 karakter"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm transition"
                            >
                            @error('password')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                id="password_confirmation" 
                                required 
                                placeholder="Ulangi kata sandi baru"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm transition"
                            >
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 active:scale-95 text-white font-bold rounded-xl text-xs uppercase tracking-wider transition shadow-xs flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span>Simpan Kata Sandi Baru</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
