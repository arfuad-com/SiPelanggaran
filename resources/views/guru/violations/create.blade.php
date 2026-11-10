@extends('layouts.app')

@section('title', 'Lapor Pelanggaran Siswa')
@section('page_title', 'Form Pelaporan Pelanggaran Siswa')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 text-sm font-bold">
                    📢
                </span>
                <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">Formulir Laporan Pelanggaran Siswa</h3>
            </div>
            <p class="text-xs sm:text-sm text-slate-500">
                Kirim catatan insiden pelanggaran siswa kepada Guru Bimbingan Konseling (BK) untuk diverifikasi.
            </p>
        </div>

        <a href="{{ route('guru.dashboard') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Beranda</span>
        </a>
    </div>

    <!-- 2-Column Responsive Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left: Form Input (8 Cols) -->
        <div class="lg:col-span-8">
            <div class="bg-white rounded-2xl p-6 sm:p-7 shadow-xs border border-slate-200/80">
                <div class="mb-6 pb-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h4 class="text-base font-bold text-slate-900">Detail Insiden Pelanggaran</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Lengkapi identitas siswa dan kronologi kejadian secara objektif.</p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-200">
                        Status Awal: Pending
                    </span>
                </div>

                <form method="POST" action="{{ route('guru.violations.store') }}" class="space-y-5">
                    @csrf

                    <!-- Pilih Siswa -->
                    <div>
                        <label for="student_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Pilih Siswa Pelanggar <span class="text-rose-500">*</span>
                        </label>
                        <select name="student_id" id="student_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm transition bg-white">
                            <option value="">-- Pilih Berdasarkan Kelas & Nama Siswa --</option>
                            @foreach($classes as $c)
                                <optgroup label="Kelas: {{ $c->name }}">
                                    @foreach($c->students as $s)
                                        <option value="{{ $s->id }}" {{ old('student_id') == $s->id ? 'selected' : '' }}>
                                            {{ $s->name }} (NISN: {{ $s->nisn }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('student_id')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori & Tanggal Kejadian -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label for="violation_category_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Jenis Pelanggaran <span class="text-rose-500">*</span>
                            </label>
                            <select name="violation_category_id" id="violation_category_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm transition bg-white">
                                <option value="">-- Pilih Jenis Pelanggaran --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('violation_category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }} (+{{ $cat->points }} Poin Sanksi)
                                    </option>
                                @endforeach
                            </select>
                            @error('violation_category_id')
                                <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="violation_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Tanggal Terjadi <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                name="violation_date" 
                                id="violation_date" 
                                value="{{ old('violation_date', date('Y-m-d')) }}" 
                                required 
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm transition bg-white"
                            >
                            @error('violation_date')
                                <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kronologi / Catatan Guru -->
                    <div>
                        <label for="notes" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Kronologi Kejadian / Catatan Guru <span class="text-rose-500">*</span>
                        </label>
                        <textarea 
                            name="notes" 
                            id="notes" 
                            rows="4" 
                            required 
                            placeholder="Contoh: Siswa ditemukan berada di luar kelas saat jam pelajaran ke-3 tanpa membawa surat izin dari guru piket..." 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm transition leading-relaxed"
                        >{{ old('notes') }}</textarea>
                        <p class="text-[11px] text-slate-400 mt-1">Uraikan kronologi singkat kejadian secara objektif untuk pertimbangan Guru BK.</p>
                        @error('notes')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-bold rounded-xl text-xs uppercase tracking-wider transition shadow-xs flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            <span>Kirim Laporan ke Guru BK</span>
                        </button>
                        <a href="{{ route('guru.dashboard') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-xs uppercase tracking-wider transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right: Verification Flow Guide (4 Cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Flow Notice Card -->
            <div class="bg-amber-50/80 border border-amber-200/80 rounded-2xl p-5 text-amber-950 shadow-xs">
                <div class="flex items-center gap-2.5 mb-2">
                    <span class="w-7 h-7 rounded-lg bg-amber-500 text-white flex items-center justify-center font-bold text-xs shadow-2xs">
                        ⏳
                    </span>
                    <h4 class="font-bold text-xs uppercase tracking-wide text-amber-950">Alur Verifikasi Laporan</h4>
                </div>
                <p class="text-xs text-amber-900/90 leading-relaxed">
                    Laporan yang Anda kirimkan akan berstatus <strong>Pending (Menunggu Validasi)</strong> terlebih dahulu. Guru BK akan menelaah kronologi insiden sebelum poin sanksi resmi ditambahkan ke siswa.
                </p>
            </div>

            <!-- Panduan Pengisian Card -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="text-slate-500 text-sm">💡</span>
                    <h4 class="font-bold text-xs text-slate-800 uppercase tracking-wider">Tips Pencatatan Yang Baik</h4>
                </div>

                <div class="space-y-2 text-xs text-slate-600">
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <strong class="text-slate-800 block text-xs">1. Kronologi Jelas</strong>
                        <p class="text-[11px] text-slate-500 mt-0.5">Sebutkan waktu spesifik, lokasi, serta saksi mata bila ada.</p>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <strong class="text-slate-800 block text-xs">2. Bersikap Objektif</strong>
                        <p class="text-[11px] text-slate-500 mt-0.5">Fokus pada fakta tindakan tanpa opini atau sentimen personal.</p>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <strong class="text-slate-800 block text-xs">3. Pantau di Riwayat Saya</strong>
                        <p class="text-[11px] text-slate-500 mt-0.5">Anda dapat memantau status persetujuan BK di menu Riwayat Laporan.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
