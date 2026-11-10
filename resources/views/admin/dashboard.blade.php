@extends('layouts.app')

@section('title', 'Dashboard Super Admin')
@section('page_title', 'Ringkasan & Log Aktivitas Global')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="bg-slate-900 rounded-xl p-5 sm:p-6 text-white border border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-lg sm:text-xl font-bold tracking-tight text-white">
                Panel Kontrol Administrator
            </h1>
            <p class="text-xs text-slate-300 mt-1 max-w-2xl leading-relaxed">
                Kelola seluruh akun pengguna, kelas, siswa, serta pantau grafik analitik pelanggaran dan konseling sekolah.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.users.create') }}" class="px-3.5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition">
                + Tambah Pengguna
            </a>
            <a href="{{ route('admin.reports.index') }}" class="px-3.5 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold text-xs border border-slate-700 transition">
                Laporan Global
            </a>
        </div>
    </div>

    <!-- Stat Metric Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Pengguna</p>
            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">{{ $totalUsers }}</p>
            <p class="text-[11px] text-slate-400 mt-0.5">Admin, BK, Guru Mapel</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Siswa</p>
            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">{{ $totalStudents }}</p>
            <p class="text-[11px] text-slate-400 mt-0.5">Seluruh kelas terdaftar</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Kasus Bulan Ini</p>
            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">{{ $totalViolationsThisMonth }}</p>
            <p class="text-[11px] text-emerald-700 font-medium mt-0.5">Status Validated</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Laporan Pending</p>
            <p class="text-2xl sm:text-3xl font-extrabold {{ $pendingReportsCount > 0 ? 'text-amber-600' : 'text-slate-900' }} mt-1">
                {{ $pendingReportsCount }}
            </p>
            <p class="text-[11px] text-slate-400 mt-0.5">Dalam antrean BK</p>
        </div>
    </div>

    <!-- Analytics Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Grafik Tren Bulanan Komparatif -->
        <div class="lg:col-span-2 bg-white p-5 rounded-xl border border-slate-200 flex flex-col justify-between">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Grafik Tren Kasus & Konseling</h3>
                        <p class="text-xs text-slate-500">Statistik seluruh data tahun {{ date('Y') }}</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs">
                        <span class="inline-flex items-center gap-1.5 font-medium text-slate-700">
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span> Kasus Valid
                        </span>
                        <span class="inline-flex items-center gap-1.5 font-medium text-slate-700">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Sesi Konseling
                        </span>
                    </div>
                </div>

                <div class="relative h-64 sm:h-72 w-full">
                    <canvas id="adminTrendChart"></canvas>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 mt-3 flex items-center justify-between text-[11px] text-slate-500">
                <span>Total Kasus Tercatat: <strong class="text-slate-800">{{ array_sum($monthlyViolations) }}</strong></span>
                <span>Total Pembinaan BK: <strong class="text-slate-800">{{ array_sum($monthlyCounseling) }}</strong></span>
            </div>
        </div>

        <!-- Grafik Proporsi Kategori Pelanggaran -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 flex flex-col justify-between">
            <div>
                <div class="mb-4 pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Proporsi Kategori Kasus</h3>
                    <p class="text-xs text-slate-500">Distribusi seluruh data pelanggaran</p>
                </div>

                <div class="relative h-56 sm:h-60 w-full flex items-center justify-center">
                    <canvas id="adminCategoryChart"></canvas>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 mt-3 text-center">
                <a href="{{ route('admin.violation-categories.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                    Kelola Master Kategori & Poin &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Activity Log Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Tabel Log Pelanggaran Terbaru (2 Columns) -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-5">
            <div class="mb-4 pb-2 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">Log Kejadian Pelanggaran Terbaru</h3>
                <p class="text-xs text-slate-500">Aktivitas pelaporan terkini dari seluruh pengguna</p>
            </div>
            
            <!-- Desktop Table View -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-100 text-slate-600 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-3">Tanggal</th>
                            <th class="py-3 px-3">Siswa & Kelas</th>
                            <th class="py-3 px-3">Pelanggaran</th>
                            <th class="py-3 px-3">Pelapor</th>
                            <th class="py-3 px-3 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y border-slate-100">
                        @forelse($recentActivities as $item)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3 px-3 text-slate-500 whitespace-nowrap">
                                    {{ $item->violation_date ? \Carbon\Carbon::parse($item->violation_date)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="py-3 px-3 whitespace-nowrap">
                                    @if($item->student)
                                        <a href="{{ route('admin.students.show', $item->student->id) }}" class="font-bold text-slate-900 hover:text-indigo-600 hover:underline transition block">
                                            {{ $item->student->name }}
                                        </a>
                                        <p class="text-[11px] text-slate-500">{{ $item->student->schoolClass?->name ?? 'Tanpa Kelas' }}</p>
                                    @else
                                        <span class="text-slate-400 italic font-medium">Siswa tidak ditemukan</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3">
                                    <span class="font-semibold text-slate-800">{{ $item->category?->name ?? 'Kategori Dihapus' }}</span>
                                    <span class="block text-[11px] text-rose-600 font-bold">+{{ $item->category?->points ?? 0 }} Poin</span>
                                </td>
                                <td class="py-3 px-3 whitespace-nowrap">
                                    @if($item->reporter)
                                        <a href="{{ route('admin.users.show', $item->reporter->id) }}" class="text-slate-600 hover:text-indigo-600 font-medium hover:underline transition">
                                            {{ $item->reporter->name }}
                                        </a>
                                    @else
                                        <span class="text-slate-400 italic">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                        {{ $item->status === 'validated' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : '' }}
                                        {{ $item->status === 'pending' ? 'bg-amber-50 text-amber-800 border border-amber-200' : '' }}
                                        {{ $item->status === 'rejected' ? 'bg-rose-50 text-rose-800 border border-rose-200' : '' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-400 font-medium">Belum ada aktivitas tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List View (Clean & No Horizontal Scroll) -->
            <div class="sm:hidden divide-y divide-slate-100">
                @forelse($recentActivities as $item)
                    <div class="py-3 space-y-1.5 first:pt-0 last:pb-0">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                @if($item->student)
                                    <a href="{{ route('admin.students.show', $item->student->id) }}" class="font-bold text-slate-900 text-xs hover:text-indigo-600 block truncate">
                                        {{ $item->student->name }}
                                    </a>
                                    <p class="text-[11px] text-slate-500">{{ $item->student->schoolClass?->name ?? 'Tanpa Kelas' }}</p>
                                @else
                                    <span class="text-slate-400 italic text-xs font-medium">Siswa tidak ditemukan</span>
                                @endif
                            </div>
                            <div class="flex flex-col items-end gap-1 flex-shrink-0">
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider
                                    {{ $item->status === 'validated' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : '' }}
                                    {{ $item->status === 'pending' ? 'bg-amber-50 text-amber-800 border border-amber-200' : '' }}
                                    {{ $item->status === 'rejected' ? 'bg-rose-50 text-rose-800 border border-rose-200' : '' }}">
                                    {{ $item->status }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-mono">
                                    {{ $item->violation_date ? \Carbon\Carbon::parse($item->violation_date)->format('d/m/Y') : '-' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-2 bg-slate-50 p-2 rounded-lg border border-slate-100">
                            <span class="text-xs font-semibold text-slate-800 truncate">{{ $item->category?->name ?? 'Kategori Dihapus' }}</span>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold text-rose-600 bg-rose-50 border border-rose-100 flex-shrink-0">
                                +{{ $item->category?->points ?? 0 }} Poin
                            </span>
                        </div>

                        <div class="flex items-center justify-between text-[11px] text-slate-500 pt-0.5">
                            <span>Pelapor:</span>
                            @if($item->reporter)
                                <a href="{{ route('admin.users.show', $item->reporter->id) }}" class="font-semibold text-indigo-600 hover:underline truncate max-w-[180px]">
                                    {{ $item->reporter->name }}
                                </a>
                            @else
                                <span class="text-slate-400 italic">-</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center text-slate-400 text-xs font-medium">
                        Belum ada aktivitas tercatat.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Tabel Log Konseling Terbaru (1 Column) -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 flex flex-col justify-between">
            <div>
                <div class="mb-4 pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Sesi Konseling Terkini</h3>
                    <p class="text-xs text-slate-500">Catatan pembinaan Guru BK</p>
                </div>
                
                <div class="space-y-3">
                    @forelse($recentCounseling as $counseling)
                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-100 text-xs">
                            <div class="flex items-center justify-between mb-1">
                                @if($counseling->student)
                                    <a href="{{ route('admin.students.show', $counseling->student->id) }}" class="font-bold text-slate-900 hover:text-indigo-600 hover:underline transition">
                                        {{ $counseling->student->name }}
                                    </a>
                                @else
                                    <span class="text-slate-400 italic">Siswa tidak ditemukan</span>
                                @endif
                                <span class="text-[10px] text-slate-400">
                                    {{ $counseling->counseling_date ? \Carbon\Carbon::parse($counseling->counseling_date)->format('d/m/Y') : '-' }}
                                </span>
                            </div>
                            <p class="text-slate-600 text-[11px] line-clamp-2 leading-relaxed">{{ $counseling->issue_summary }}</p>
                            <p class="text-[10px] text-indigo-700 mt-1 font-semibold">
                                Konselor: 
                                @if($counseling->counselor)
                                    <a href="{{ route('admin.users.show', $counseling->counselor->id) }}" class="hover:underline">
                                        {{ $counseling->counselor->name }}
                                    </a>
                                @else
                                    <span class="text-slate-400 italic">-</span>
                                @endif
                            </p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-6 text-center font-medium">Belum ada riwayat konseling.</p>
                    @endforelse
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 mt-4 text-center">
                <a href="{{ route('admin.reports.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                    Buka Laporan Global Selengkapnya &rarr;
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const palette = ['#4f46e5', '#0ea5e9', '#f59e0b', '#ef4444', '#8b5cf6', '#10b981', '#64748b'];

        // 1. Tren Kasus & Konseling Bulanan Admin
        const trendCanvas = document.getElementById('adminTrendChart');
        if (trendCanvas) {
            const ctxTrend = trendCanvas.getContext('2d');
            const months = @json($chartMonths);
            const violationData = @json($monthlyViolations);
            const counselingData = @json($monthlyCounseling);

            new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: 'Kasus Valid',
                            data: violationData,
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.08)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#4f46e5',
                            pointRadius: 3,
                            pointHoverRadius: 6,
                        },
                        {
                            label: 'Sesi Konseling',
                            data: counselingData,
                            borderColor: '#10b981',
                            backgroundColor: 'transparent',
                            borderWidth: 2,
                            borderDash: [4, 4],
                            tension: 0.35,
                            pointBackgroundColor: '#10b981',
                            pointRadius: 3,
                            pointHoverRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 12, family: 'Inter', weight: '600' },
                            bodyFont: { size: 11, family: 'Inter' },
                            padding: 10,
                            cornerRadius: 8,
                            usePointStyle: true,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: '#94a3b8', font: { family: 'Inter', size: 11 } },
                            grid: { color: '#f1f5f9' },
                        },
                        x: {
                            ticks: { color: '#94a3b8', font: { family: 'Inter', size: 11 } },
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // 2. Proporsi Kategori Pelanggaran Admin
        const catCanvas = document.getElementById('adminCategoryChart');
        if (catCanvas) {
            const ctxCat = catCanvas.getContext('2d');
            const catLabels = @json($categoryLabels);
            const catCounts = @json($categoryCounts);

            new Chart(ctxCat, {
                type: 'doughnut',
                data: {
                    labels: catLabels,
                    datasets: [{
                        data: catCounts,
                        backgroundColor: palette.slice(0, catLabels.length),
                        borderWidth: 2,
                        borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                font: { size: 10, family: 'Inter' },
                                color: '#475569',
                                padding: 12,
                            }
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 11, family: 'Inter', weight: '600' },
                            bodyFont: { size: 11, family: 'Inter' },
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const value = context.parsed;
                                    const pct = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return ` ${context.label}: ${value} kasus (${pct}%)`;
                                }
                            }
                        }
                    },
                    cutout: '65%'
                }
            });
        }
    });
</script>
@endpush
