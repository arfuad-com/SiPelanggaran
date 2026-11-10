<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CounselingRecord;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentViolation;
use App\Models\User;
use App\Models\ViolationCategory;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $now = now();
        $totalUsers = User::count();
        $totalStudents = Student::count();
        $totalViolationsThisMonth = StudentViolation::where('status', 'validated')
            ->whereMonth('violation_date', $now->month)
            ->whereYear('violation_date', $now->year)
            ->count();
        $pendingReportsCount = StudentViolation::where('status', 'pending')->count();

        $recentActivities = StudentViolation::with(['student.schoolClass', 'category', 'reporter', 'validator'])
            ->latest()
            ->take(8)
            ->get();

        $recentCounseling = CounselingRecord::with(['student.schoolClass', 'counselor'])
            ->latest()
            ->take(5)
            ->get();

        // Data Grafik Tren Bulanan & Sebaran Data
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

        $categoryData = ViolationCategory::withCount(['violations' => function ($q) {
            $q->where('status', 'validated');
        }])
            ->orderByDesc('violations_count')
            ->get();

        $categoryLabels = $categoryData->pluck('name')->map(function ($name) {
            return strlen($name) > 30 ? substr($name, 0, 27).'...' : $name;
        })->toArray();
        $categoryCounts = $categoryData->pluck('violations_count')->toArray();

        $classData = SchoolClass::withCount(['violations' => function ($q) {
            $q->where('student_violations.status', 'validated');
        }])
            ->orderByDesc('violations_count')
            ->take(6)
            ->get();

        $classLabels = $classData->pluck('name')->toArray();
        $classCounts = $classData->pluck('violations_count')->toArray();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalStudents',
            'totalViolationsThisMonth',
            'pendingReportsCount',
            'recentActivities',
            'recentCounseling',
            'chartMonths',
            'monthlyViolations',
            'monthlyCounseling',
            'categoryLabels',
            'categoryCounts',
            'classLabels',
            'classCounts'
        ));
    }
}
