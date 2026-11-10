<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CounselingRecord;
use App\Models\SchoolClass;
use App\Models\StudentViolation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $classes = SchoolClass::orderBy('name')->get();

        // 1. Rekapitulasi Pelanggaran
        $violationQuery = StudentViolation::with(['student.schoolClass', 'category', 'reporter', 'validator'])
            ->where('status', 'validated');

        if ($request->filled('start_date')) {
            $violationQuery->whereDate('violation_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $violationQuery->whereDate('violation_date', '<=', $request->end_date);
        }

        if ($request->filled('class_id')) {
            $violationQuery->whereHas('student', function ($q) use ($request) {
                $q->where('school_class_id', $request->class_id);
            });
        }

        $violations = $violationQuery->latest('violation_date')->paginate(15, ['*'], 'violations_page')->withQueryString();

        // 2. Rekapitulasi Konseling
        $counselingQuery = CounselingRecord::with(['student.schoolClass', 'counselor']);

        if ($request->filled('start_date')) {
            $counselingQuery->whereDate('counseling_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $counselingQuery->whereDate('counseling_date', '<=', $request->end_date);
        }

        if ($request->filled('class_id')) {
            $counselingQuery->whereHas('student', function ($q) use ($request) {
                $q->where('school_class_id', $request->class_id);
            });
        }

        $counselings = $counselingQuery->latest('counseling_date')->paginate(15, ['*'], 'counselings_page')->withQueryString();

        return view('admin.reports.index', compact('classes', 'violations', 'counselings'));
    }
}
