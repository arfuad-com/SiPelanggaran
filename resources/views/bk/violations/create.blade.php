@extends('layouts.app')

@section('title', 'Input Kasus Pelanggaran Langsung')
@section('page_title', 'Buku Kasus: Input Pelanggaran Langsung (Guru BK)')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 text-sm font-bold">
                    📝
                </span>
                <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">Catat Kasus Pelanggaran Langsung</h3>
            </div>
            <p class="text-xs sm:text-sm text-slate-500">
                Pencatatan kasus resmi dari Guru Bimbingan Konseling dengan validasi langsung ke buku poin siswa.
            </p>
        </div>

        <a href="{{ route('bk.dashboard') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Analitik</span>
        </a>
    </div>

    <!-- 2-Column Responsive Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left: Form Input (8 Cols) -->
        <div class="lg:col-span-8">
            <div class="bg-white rounded-2xl p-6 sm:p-7 shadow-xs border border-slate-200/80">
                <div class="mb-6 pb-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h4 class="text-base font-bold text-slate-900">Formulir Catatan Kasus Siswa</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Isi data insiden pelanggaran siswa dengan lengkap dan objektif.</p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-100">
                        Otoritas BK
                    </span>
                </div>

                <form method="POST" action="{{ route('bk.violations.store') }}" class="space-y-5">
                    @csrf

                    <!-- Pilih Siswa -->
                    <div>
                        <label for="student_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Pilih Siswa Pelanggar <span class="text-rose-500">*</span>
                        </label>
                        <select name="student_id" id="student_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm transition bg-white">
                            <option value="">-- Cari dan Pilih Siswa --</option>
                            @foreach($classes as $c)
                                <optgroup label="Kelas: {{ $c->name }}">
                                    @foreach($c->students as $s)
                                        <option value="{{ $s->id }}" {{ old('student_id') == $s->id ? 'selected' : '' }}>
                                            {{ $s->name }} (NISN: {{ $s->nisn }}) — Poin Saat Ini: {{ $s->total_violation_points }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('student_id')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori & Bobot Poin -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label for="violation_category_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Jenis Pelanggaran & Sanksi <span class="text-rose-500">*</span>
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
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="violation_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Tanggal Kejadian <span class="text-rose-500">*</span>
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
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kronologi / Catatan Kejadian -->
                    <div>
                        <label for="notes" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Kronologi Kejadian & Catatan Tindak Lanjut
                        </label>
                        <textarea 
                            name="notes" 
                            id="notes" 
                            rows="4" 
                            placeholder="Contoh: Siswa dipanggil ke ruang BK setelah ditemukan meninggalkan kelas tanpa izin saat jam istirahat kedua. Telah diberikan peringatan dan komitmen tertulis..." 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-sm transition"
                        >{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-bold rounded-xl text-xs uppercase tracking-wider transition shadow-xs flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Simpan & Validasi Langsung</span>
                        </button>

                        <a href="{{ route('bk.dashboard') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-xs uppercase tracking-wider transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right: Guides & Reference Cards (4 Cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Notice Otoritas Card -->
            <div class="bg-indigo-50/80 border border-indigo-100 rounded-2xl p-5 text-indigo-900 shadow-xs">
                <div class="flex items-center gap-2.5 mb-2">
                    <span class="w-7 h-7 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold text-xs shadow-2xs">
                        ⚡
                    </span>
                    <h4 class="font-bold text-xs uppercase tracking-wide text-indigo-950">Status Validasi Otomatis</h4>
                </div>
                <p class="text-xs text-indigo-800/90 leading-relaxed">
                    Setiap laporan yang dicatat oleh <strong>Guru BK</strong> otomatis berstatus <strong>Validated</strong> tanpa melalui antrean verifikasi, dan bobot poin sanksi akan <strong>langsung diakumulasikan</strong> ke total poin siswa yang bersangkutan.
                </p>
            </div>

            <!-- Referensi Ambang Batas Poin -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="text-slate-500 text-sm">📊</span>
                    <h4 class="font-bold text-xs text-slate-800 uppercase tracking-wider">Tingkatan Bobot Sanksi</h4>
                </div>

                <div class="space-y-2.5 text-xs">
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-bold text-slate-700">Tingkat Ringan</span>
                            <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 font-bold text-[10px] border border-emerald-200">1 - 20 Poin</span>
                        </div>
                        <p class="text-[11px] text-slate-500">Teguran lisan & pembinaan dari wali kelas.</p>
                    </div>

                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-bold text-slate-700">Tingkat Sedang</span>
                            <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-700 font-bold text-[10px] border border-amber-200">21 - 50 Poin</span>
                        </div>
                        <p class="text-[11px] text-slate-500">Panggilan konseling BK & surat peringatan I.</p>
                    </div>

                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-bold text-slate-700">Tingkat Berat</span>
                            <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-700 font-bold text-[10px] border border-rose-200">51 - 99 Poin</span>
                        </div>
                        <p class="text-[11px] text-slate-500">Panggilan orang tua & surat peringatan II/III.</p>
                    </div>

                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-bold text-slate-700">Tindakan Khusus</span>
                            <span class="px-2 py-0.5 rounded bg-red-100 text-red-800 font-black text-[10px] border border-red-200">100+ Poin</span>
                        </div>
                        <p class="text-[11px] text-slate-500">Konferensi kasus dan opsi pengembalian ke wali.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
