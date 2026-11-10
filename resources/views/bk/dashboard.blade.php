@extends('layouts.app')

@section('title', 'Pusat Analitik BK')
@section('page_title', 'Pusat Analitik & Pemantauan Perilaku Siswa')

@section('content')
<div class="space-y-6">

    <!-- Header Section (Clean, Professional & Modern) -->
    <div class="bg-slate-900 rounded-xl p-5 sm:p-6 text-white border border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-lg sm:text-xl font-bold tracking-tight text-white">
                Dashboard Analitik Bimbingan & Konseling
            </h1>
            <p class="text-xs text-slate-300 mt-1 max-w-2xl leading-relaxed">
                Visualisasi komprehensif data perilaku siswa, perbandingan tren penanganan konseling, dan pemetaan kedisiplinan per kelas.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('bk.violations.create') }}" class="px-3.5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition">
                + Input Kasus (Langsung)
            </a>
            <a href="{{ route('bk.counseling.create') }}" class="px-3.5 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold text-xs border border-slate-700 transition">
                + Catat Konseling
            </a>
        </div>
    </div>

    <!-- Alert Notifikasi Laporan Pending Masuk -->
    @if($pendingCount > 0)
        <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
            <div class="flex items-center gap-3">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping"></span>
                <div>
                    <h4 class="font-bold text-slate-900">
                        {{ $pendingCount }} Laporan Pelanggaran Menunggu Validasi
                    </h4>
                    <p class="text-slate-600 text-[11px] mt-0.5">
                        Laporan masuk dari guru mata pelajaran/wali kelas memerlukan persetujuan BK sebelum poin diakumulasikan.
                    </p>
                </div>
            </div>
            <a href="{{ route('bk.validations.index') }}" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-lg transition whitespace-nowrap self-start sm:self-auto">
                Buka Antrean Validasi &rarr;
            </a>
        </div>
    @endif

    <!-- Metric Stats Grid (Responsive 2 cols on mobile, 4 on desktop) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Kasus Bulan Ini</p>
            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">{{ $totalViolationsThisMonth }}</p>
            <p class="text-[11px] text-emerald-700 font-medium mt-1">Status Validated</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Antrean Validasi</p>
            <p class="text-2xl sm:text-3xl font-extrabold {{ $pendingCount > 0 ? 'text-amber-600' : 'text-slate-900' }} mt-1">
                {{ $pendingCount }}
            </p>
            <p class="text-[11px] text-slate-500 mt-1">Laporan Pending</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Sesi Konseling</p>
            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">{{ $totalCounselingSessions }}</p>
            <p class="text-[11px] text-slate-500 mt-1">Total Pembinaan</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Siswa Kritis</p>
            <p class="text-2xl sm:text-3xl font-extrabold text-rose-600 mt-1">{{ $criticalStudentsCount }}</p>
            <p class="text-[11px] text-rose-600 font-medium mt-1">Poin &ge; 50</p>
        </div>
    </div>

    <!-- Row 1: Primary Analytics (Monthly Trend vs Category Breakdown) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Grafik 1: Tren Komparatif Bulanan (Pelanggaran Sah vs Sesi Konseling) -->
        <div class="lg:col-span-2 bg-white p-5 rounded-xl border border-slate-200 flex flex-col justify-between">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Grafik Tren Pelanggaran & Konseling Bulanan</h3>
                        <p class="text-xs text-slate-500">Perbandingan kasus tervalidasi dengan intervensi bimbingan tahun {{ date('Y') }}</p>
                    </div>
                    <div class="flex items-center gap-3 text-xs">
                        <span class="inline-flex items-center gap-1.5 font-medium text-slate-700">
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span> Pelanggaran
                        </span>
                        <span class="inline-flex items-center gap-1.5 font-medium text-slate-700">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Konseling
                        </span>
                    </div>
                </div>

                <div class="relative h-64 sm:h-72 w-full">
                    <canvas id="monthlyComparativeChart"></canvas>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 mt-4 flex items-center justify-between text-[11px] text-slate-500">
                <span>Total Pelanggaran Tahun Ini: <strong class="text-slate-800">{{ array_sum($monthlyViolations) }}</strong></span>
                <span>Total Konseling Tahun Ini: <strong class="text-slate-800">{{ array_sum($monthlyCounseling) }}</strong></span>
            </div>
        </div>

        <!-- Grafik 2: Proporsi Kategori Pelanggaran (Doughnut Chart) -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 flex flex-col justify-between">
            <div>
                <div class="mb-4 pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Distribusi Kategori Pelanggaran</h3>
                    <p class="text-xs text-slate-500">Komposisi seluruh kasus kedisiplinan</p>
                </div>

                <div class="relative h-56 sm:h-60 w-full flex items-center justify-center">
                    <canvas id="categoryDoughnutChart"></canvas>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 mt-3">
                <p class="text-[11px] text-slate-500 text-center">
                    Total {{ count($categoryLabels) }} jenis kategori pelanggaran tercatat dalam sistem.
                </p>
            </div>
        </div>
    </div>

    <!-- Row 2: Secondary Analytics (Class Distribution & Status Breakdown & Top Violators) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Grafik 3: Sebaran Pelanggaran Berdasarkan Kelas (Bar Chart) -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 flex flex-col justify-between">
            <div>
                <div class="mb-4 pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Sebaran Kasus per Kelas</h3>
                    <p class="text-xs text-slate-500">Kelas dengan frekuensi pelanggaran terbanyak</p>
                </div>

                <div class="relative h-56 sm:h-64 w-full">
                    <canvas id="classBarChart"></canvas>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 mt-3 text-center">
                <a href="{{ route('bk.students.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                    Lihat Daftar Siswa Tiap Kelas &rarr;
                </a>
            </div>
        </div>

        <!-- Grafik 4: Status Alur Validasi Laporan (Status Doughnut) -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 flex flex-col justify-between">
            <div>
                <div class="mb-4 pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Status Validasi Laporan</h3>
                    <p class="text-xs text-slate-500">Efisiensi verifikasi kasus kedisiplinan</p>
                </div>

                <div class="relative h-44 sm:h-48 w-full flex items-center justify-center">
                    <canvas id="statusDoughnutChart"></canvas>
                </div>

                <div class="grid grid-cols-3 gap-2 mt-4 text-center">
                    <div class="p-2 rounded-lg bg-emerald-50 border border-emerald-100">
                        <p class="text-[10px] text-emerald-700 font-semibold uppercase">Sah</p>
                        <p class="text-sm font-extrabold text-emerald-800">{{ $statusCounts['validated'] }}</p>
                    </div>
                    <div class="p-2 rounded-lg bg-amber-50 border border-amber-100">
                        <p class="text-[10px] text-amber-700 font-semibold uppercase">Pending</p>
                        <p class="text-sm font-extrabold text-amber-800">{{ $statusCounts['pending'] }}</p>
                    </div>
                    <div class="p-2 rounded-lg bg-rose-50 border border-rose-100">
                        <p class="text-[10px] text-rose-700 font-semibold uppercase">Ditolak</p>
                        <p class="text-sm font-extrabold text-rose-800">{{ $statusCounts['rejected'] }}</p>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 mt-3 text-center">
                <a href="{{ route('bk.validations.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                    Buka Antrean Validasi &rarr;
                </a>
            </div>
        </div>

        <!-- Top Violators Table -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 flex flex-col justify-between">
            <div>
                <div class="mb-4 pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Akumulasi Poin Tertinggi</h3>
                    <p class="text-xs text-slate-500">Prioritas perhatian Guru BK</p>
                </div>

                <div class="space-y-2.5">
                    @forelse($topViolators as $index => $student)
                        <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-between gap-3 text-xs">
                            <div class="min-w-0 flex items-center gap-2.5">
                                <span class="font-bold text-slate-500 text-[11px] w-4 text-center">
                                    {{ $index + 1 }}.
                                </span>
                                <div class="min-w-0">
                                    <a href="{{ route('bk.students.show', $student->id) }}" class="font-bold text-slate-800 hover:text-indigo-600 truncate block">
                                        {{ $student->name }}
                                    </a>
                                    <p class="text-[11px] text-slate-500">{{ $student->schoolClass->name }}</p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="px-2 py-0.5 rounded text-xs font-bold {{ $student->total_violation_points >= 50 ? 'bg-rose-100 text-rose-800' : 'bg-slate-200 text-slate-800' }}">
                                    {{ $student->total_violation_points }} Poin
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-8 text-center">Belum ada data pelanggaran siswa.</p>
                    @endforelse
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 mt-4 text-center">
                <a href="{{ route('bk.students.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                    Buka Direktori Seluruh Siswa &rarr;
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Palet Warna Standar Antarmuka Profesional
        const palette = ['#4f46e5', '#0ea5e9', '#f59e0b', '#ef4444', '#8b5cf6', '#10b981', '#64748b'];

        // 1. Grafik Tren Komparatif Bulanan (Line Chart)
        const compCanvas = document.getElementById('monthlyComparativeChart');
        if (compCanvas) {
            const ctxComp = compCanvas.getContext('2d');
            const months = @json($chartMonths);
            const violationData = @json($monthlyViolations);
            const counselingData = @json($monthlyCounseling);

            new Chart(ctxComp, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: 'Pelanggaran Sah',
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
                            label: 'Sesi Konseling BK',
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

        // 2. Grafik Distribusi Kategori Pelanggaran (Doughnut Chart)
        const catCanvas = document.getElementById('categoryDoughnutChart');
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

        // 3. Grafik Sebaran Pelanggaran per Kelas (Horizontal Bar Chart)
        const classCanvas = document.getElementById('classBarChart');
        if (classCanvas) {
            const ctxClass = classCanvas.getContext('2d');
            const classLabels = @json($classLabels);
            const classCounts = @json($classCounts);

            new Chart(ctxClass, {
                type: 'bar',
                data: {
                    labels: classLabels,
                    datasets: [{
                        label: 'Jumlah Kasus',
                        data: classCounts,
                        backgroundColor: '#6366f1',
                        borderRadius: 6,
                        maxBarThickness: 24,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 11, family: 'Inter', weight: '600' },
                            bodyFont: { size: 11, family: 'Inter' },
                            padding: 10,
                            cornerRadius: 8,
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: '#94a3b8', font: { family: 'Inter', size: 10 } },
                            grid: { color: '#f1f5f9' },
                        },
                        y: {
                            ticks: { color: '#475569', font: { family: 'Inter', size: 11, weight: '500' } },
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        // 4. Grafik Status Validasi Laporan (Doughnut Chart)
        const statusCanvas = document.getElementById('statusDoughnutChart');
        if (statusCanvas) {
            const ctxStatus = statusCanvas.getContext('2d');
            const statusData = @json(array_values($statusCounts));

            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: ['Tervalidasi (Sah)', 'Menunggu Validasi', 'Ditolak'],
                    datasets: [{
                        data: statusData,
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 11, family: 'Inter', weight: '600' },
                            bodyFont: { size: 11, family: 'Inter' },
                            padding: 10,
                            cornerRadius: 8,
                        }
                    },
                    cutout: '70%'
                }
            });
        }
    });
</script>
@endpush
