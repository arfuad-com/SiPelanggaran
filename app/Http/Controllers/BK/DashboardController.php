<?php

namespace App\Http\Controllers\BK;

use App\Http\Controllers\Controller;
use App\Models\CounselingRecord;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentViolation;
use App\Models\ViolationCategory;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $now = now();
        $pendingCount = StudentViolation::where('status', 'pending')->count();
        $totalViolationsThisMonth = StudentViolation::where('status', 'validated')
            ->whereMonth('violation_date', $now->month)
            ->whereYear('violation_date', $now->year)
            ->count();
        $totalCounselingSessions = CounselingRecord::count();
        $criticalStudentsCount = Student::where('total_violation_points', '>=', 50)->count();

        // Top 5 Siswa dengan Poin Pelanggaran Tertinggi
        $topViolators = Student::with('schoolClass')
            ->orderByDesc('total_violation_points')
            ->take(5)
            ->get();

        // 1. Data 12 Bulan (Tren Komparatif: Pelanggaran Sah vs Sesi Konseling)
        $chartMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlyViolations = [];
        $monthlyCounseling = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthlyViolations[] = StudentViolation::where('status', 'validated')
                ->whereYear('violation_date', $now->year)
                ->whereMonth('violation_date', $m)
                ->count();

            $monthlyCounseling[] = CounselingRecord::whereYear('counseling_date', $now->year)
                ->whereMonth('counseling_date', $m)
                ->count();
        }

        // 2. Data Kategori Pelanggaran (Distribusi Sesuai Semua Kategori)
        $categoryData = ViolationCategory::withCount(['violations' => function ($q) {
            $q->where('status', 'validated');
        }])
            ->orderByDesc('violations_count')
            ->get();

        $categoryLabels = $categoryData->pluck('name')->map(function ($name) {
            return strlen($name) > 30 ? substr($name, 0, 27).'...' : $name;
        })->toArray();
        $categoryCounts = $categoryData->pluck('violations_count')->toArray();

        // 3. Data Pelanggaran per Kelas (Sebaran Tingkat Kelas)
        $classData = SchoolClass::withCount(['violations' => function ($q) {
            $q->where('student_violations.status', 'validated');
        }])
            ->orderByDesc('violations_count')
            ->take(6)
            ->get();

        $classLabels = $classData->pluck('name')->toArray();
        $classCounts = $classData->pluck('violations_count')->toArray();

        // 4. Data Distribusi Status Laporan Kasus
        $statusCounts = [
            'validated' => StudentViolation::where('status', 'validated')->count(),
            'pending' => $pendingCount,
            'rejected' => StudentViolation::where('status', 'rejected')->count(),
        ];

        return view('bk.dashboard', compact(
            'pendingCount',
            'totalViolationsThisMonth',
            'totalCounselingSessions',
            'criticalStudentsCount',
            'topViolators',
            'chartMonths',
            'monthlyViolations',
            'monthlyCounseling',
            'categoryLabels',
            'categoryCounts',
            'classLabels',
            'classCounts',
            'statusCounts'
        ));
    }
}
