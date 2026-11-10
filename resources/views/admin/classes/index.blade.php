@extends('layouts.app')

@section('title', 'Data Kelas & Jurusan')
@section('page_title', 'Master Data Kelas dan Jurusan')

@section('content')
<div class="space-y-8">
    <!-- Grid 2 Kolom: Jurusan & Kelas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- KOLOM 1: MASTER JURUSAN -->
        <div class="space-y-4">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="text-base font-bold text-slate-800 mb-1">Tambah Jurusan Baru</h3>
                <p class="text-xs text-slate-500 mb-4">Tambahkan program keahlian/jurusan sekolah.</p>

                <form method="POST" action="{{ route('admin.majors.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kode Jurusan</label>
                        <input type="text" name="code" placeholder="Contoh: RPL, TKJ, DKV" required class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs uppercase font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap Jurusan</label>
                        <input type="text" name="name" placeholder="Contoh: Rekayasa Perangkat Lunak" required class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider transition">
                        + Tambah Jurusan
                    </button>
                </form>
            </div>

            <!-- Tabel Jurusan -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 font-bold text-slate-800 text-sm">
                    Daftar Jurusan Terdaftar
                </div>
                
                <!-- Desktop Table View -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 font-bold uppercase">
                            <tr>
                                <th class="py-3 px-4">Kode</th>
                                <th class="py-3 px-4">Nama Jurusan</th>
                                <th class="py-3 px-4 text-center">Jumlah Kelas</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($majors as $major)
                                <tr>
                                    <td class="py-3 px-4 font-mono font-bold text-indigo-600">{{ $major->code }}</td>
                                    <td class="py-3 px-4 font-semibold text-slate-800">{{ $major->name }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="px-2 py-0.5 rounded-full bg-slate-100 font-bold text-slate-600">{{ $major->classes_count }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <form method="POST" action="{{ route('admin.majors.destroy', $major->id) }}" class="inline" onsubmit="return confirm('Hapus jurusan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 font-semibold text-xs">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-slate-400">Belum ada jurusan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card List View -->
                <div class="sm:hidden divide-y divide-slate-100">
                    @forelse($majors as $major)
                        <div class="p-3.5 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-indigo-600 text-xs">{{ $major->code }}</span>
                                    <span class="px-2 py-0.2 rounded-full bg-slate-100 font-bold text-[10px] text-slate-600">{{ $major->classes_count }} Kelas</span>
                                </div>
                                <p class="font-semibold text-slate-800 text-xs mt-0.5 truncate">{{ $major->name }}</p>
                            </div>
                            <form method="POST" action="{{ route('admin.majors.destroy', $major->id) }}" onsubmit="return confirm('Hapus jurusan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2.5 py-1 text-rose-600 hover:bg-rose-50 rounded-lg font-semibold text-xs transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="p-6 text-center text-slate-400 text-xs">Belum ada jurusan.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- KOLOM 2: MASTER KELAS -->
        <div class="space-y-4">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="text-base font-bold text-slate-800 mb-1">Tambah Kelas Baru</h3>
                <p class="text-xs text-slate-500 mb-4">Tambahkan rombel/kelas siswa.</p>

                <form method="POST" action="{{ route('admin.classes.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pilih Jurusan</label>
                        <select name="major_id" required class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs">
                            <option value="">-- Pilih Jurusan --</option>
                            @foreach($majors as $m)
                                <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Rombel / Kelas</label>
                        <input type="text" name="name" placeholder="Contoh: X RPL 1, XII TKJ 2" required class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider transition">
                        + Tambah Kelas
                    </button>
                </form>
            </div>

            <!-- Tabel Kelas -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 font-bold text-slate-800 text-sm">
                    Daftar Kelas Terdaftar
                </div>

                <!-- Desktop Table View -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 font-bold uppercase">
                            <tr>
                                <th class="py-3 px-4">Nama Kelas</th>
                                <th class="py-3 px-4">Jurusan</th>
                                <th class="py-3 px-4 text-center">Jumlah Siswa</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($classes as $c)
                                <tr>
                                    <td class="py-3 px-4 font-bold text-slate-800">{{ $c->name }}</td>
                                    <td class="py-3 px-4 text-slate-600">{{ $c->major->code }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="px-2 py-0.5 rounded-full bg-slate-100 font-bold text-slate-600">{{ $c->students_count }} Siswa</span>
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <form method="POST" action="{{ route('admin.classes.destroy', $c->id) }}" class="inline" onsubmit="return confirm('Hapus kelas ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 font-semibold text-xs">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-slate-400">Belum ada kelas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card List View -->
                <div class="sm:hidden divide-y divide-slate-100">
                    @forelse($classes as $c)
                        <div class="p-3.5 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="font-bold text-slate-800 text-xs truncate">{{ $c->name }}</p>
                                <div class="flex items-center gap-2 mt-0.5 text-[11px] text-slate-500">
                                    <span>{{ $c->major->code }}</span>
                                    <span>•</span>
                                    <span class="text-indigo-600 font-semibold">{{ $c->students_count }} Siswa</span>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('admin.classes.destroy', $c->id) }}" onsubmit="return confirm('Hapus kelas ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2.5 py-1 text-rose-600 hover:bg-rose-50 rounded-lg font-semibold text-xs transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="p-6 text-center text-slate-400 text-xs">Belum ada kelas.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
