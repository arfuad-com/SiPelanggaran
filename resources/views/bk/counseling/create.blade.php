@extends('layouts.app')

@section('title', 'Catat Sesi Konseling')
@section('page_title', 'Form Pencatatan Bimbingan Konseling')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 text-sm font-bold">
                    💬
                </span>
                <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">Catat Sesi Bimbingan & Konseling</h3>
            </div>
            <p class="text-xs sm:text-sm text-slate-500">
                Dokumentasikan dialog pembinaan, diagnosis latar belakang masalah, dan komitmen perbaikan siswa.
            </p>
        </div>

        <a href="{{ route('bk.counseling.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Daftar Sesi Konseling</span>
        </a>
    </div>

    <!-- 2-Column Responsive Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left: Form Input (8 Cols) -->
        <div class="lg:col-span-8">
            <div class="bg-white rounded-2xl p-6 sm:p-7 shadow-xs border border-slate-200/80">
                <div class="mb-6 pb-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h4 class="text-base font-bold text-slate-900">Formulir Notula Sesi Konseling</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Catatan ini bersifat rahasia dan menjadi rekam jejak bimbingan siswa.</p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Sesi Personal
                    </span>
                </div>

                <form method="POST" action="{{ route('bk.counseling.store') }}" class="space-y-5">
                    @csrf

                    <!-- Pilih Siswa & Tanggal -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label for="student_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Nama Siswa <span class="text-rose-500">*</span>
                            </label>
                            <select name="student_id" id="student_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition bg-white">
                                <option value="">-- Pilih Siswa --</option>
                                @foreach($classes as $c)
                                    <optgroup label="Kelas: {{ $c->name }}">
                                        @foreach($c->students as $s)
                                            <option value="{{ $s->id }}" {{ (old('student_id') == $s->id || $selectedStudentId == $s->id) ? 'selected' : '' }}>
                                                {{ $s->name }} (Poin: {{ $s->total_violation_points }})
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('student_id')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="counseling_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Tanggal Sesi <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                name="counseling_date" 
                                id="counseling_date" 
                                value="{{ old('counseling_date', date('Y-m-d')) }}" 
                                required 
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition bg-white"
                            >
                            @error('counseling_date')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Permasalahan -->
                    <div>
                        <label for="issue_summary" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Permasalahan yang Dihadapi / Keluhan Perilaku <span class="text-rose-500">*</span>
                        </label>
                        <textarea 
                            name="issue_summary" 
                            id="issue_summary" 
                            rows="3" 
                            required 
                            placeholder="Contoh: Siswa sering terlambat dan terindikasi terpengaruh game online larut malam..." 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition leading-relaxed"
                        >{{ old('issue_summary') }}</textarea>
                        @error('issue_summary')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hasil Pembinaan -->
                    <div>
                        <label for="counseling_result" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Hasil Pembinaan & Dialog Konseling <span class="text-rose-500">*</span>
                        </label>
                        <textarea 
                            name="counseling_result" 
                            id="counseling_result" 
                            rows="3" 
                            required 
                            placeholder="Contoh: Siswa menyadari kesalahannya, bersedia membatasi gadget dan berkomitmen hadir tepat waktu..." 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition leading-relaxed"
                        >{{ old('counseling_result') }}</textarea>
                        @error('counseling_result')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tindak Lanjut -->
                    <div>
                        <label for="follow_up_action" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Rencana Tindak Lanjut & Monitoring <span class="text-rose-500">*</span>
                        </label>
                        <textarea 
                            name="follow_up_action" 
                            id="follow_up_action" 
                            rows="2" 
                            required 
                            placeholder="Contoh: Pemantauan presensi harian bersama wali kelas selama 2 minggu dan evaluasi sesi kedua..." 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 text-sm transition leading-relaxed"
                        >{{ old('follow_up_action') }}</textarea>
                        @error('follow_up_action')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                        <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold rounded-xl text-xs uppercase tracking-wider transition shadow-xs flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Simpan Sesi Bimbingan</span>
                        </button>
                        <a href="{{ route('bk.counseling.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-xs uppercase tracking-wider transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right: Pedoman & Kerahasiaan (4 Cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Asas Kerahasiaan Card -->
            <div class="bg-emerald-50/80 border border-emerald-200/80 rounded-2xl p-5 text-emerald-950 shadow-xs">
                <div class="flex items-center gap-2.5 mb-2">
                    <span class="w-7 h-7 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold text-xs shadow-2xs">
                        🔒
                    </span>
                    <h4 class="font-bold text-xs uppercase tracking-wide text-emerald-950">Asas Kerahasiaan BK</h4>
                </div>
                <p class="text-xs text-emerald-900/90 leading-relaxed">
                    Setiap isi konseling dilindungi kode etik kerahasiaan konselor. Informasi ini ditujukan khusus untuk pemantauan perkembangan kepribadian siswa secara profesional.
                </p>
            </div>

            <!-- Tahapan Konseling -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <span class="text-slate-500 text-sm">📋</span>
                    <h4 class="font-bold text-xs text-slate-800 uppercase tracking-wider">Tahapan Sesi Efektif</h4>
                </div>

                <div class="space-y-2 text-xs text-slate-600">
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <strong class="text-slate-800 block text-xs">1. Identifikasi Masalah</strong>
                        <p class="text-[11px] text-slate-500 mt-0.5">Dengarkan penjelasan siswa secara empatik tanpa menghakimi.</p>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <strong class="text-slate-800 block text-xs">2. Kesepakatan Solusi</strong>
                        <p class="text-[11px] text-slate-500 mt-0.5">Ajak siswa merumuskan solusi dan komitmen perbaikan sendiri.</p>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <strong class="text-slate-800 block text-xs">3. Tindak Lanjut Berkala</strong>
                        <p class="text-[11px] text-slate-500 mt-0.5">Koordinasikan pengawasan dengan wali kelas dan orang tua.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
