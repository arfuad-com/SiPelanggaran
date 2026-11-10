<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentViolation;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $userId = Auth::id();
        $totalStudents = Student::count();

        $myTotalReports = StudentViolation::where('reported_by', $userId)->count();
        $myPendingReports = StudentViolation::where('reported_by', $userId)->where('status', 'pending')->count();
        $myValidatedReports = StudentViolation::where('reported_by', $userId)->where('status', 'validated')->count();
        $myRejectedReports = StudentViolation::where('reported_by', $userId)->where('status', 'rejected')->count();

        $recentReports = StudentViolation::with(['student.schoolClass', 'category', 'validator'])
            ->where('reported_by', $userId)
            ->latest()
            ->take(6)
            ->get();

        return view('guru.dashboard', compact(
            'totalStudents',
            'myTotalReports',
            'myPendingReports',
            'myValidatedReports',
            'myRejectedReports',
            'recentReports'
        ));
    }
}
