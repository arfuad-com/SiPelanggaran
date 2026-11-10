<?php

namespace App\Http\Controllers\BK;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentTrackController extends Controller
{
    public function index(Request $request): View
    {
        $query = Student::with(['schoolClass.major']);

        if ($request->filled('class_id')) {
            $query->where('school_class_id', $request->class_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $students = $query->orderByDesc('total_violation_points')->paginate(12)->withQueryString();
        $classes = SchoolClass::orderBy('name')->get();

        return view('bk.students.index', compact('students', 'classes'));
    }

    public function show(Student $student): View
    {
        $student->load([
            'schoolClass.major',
            'violations' => function ($q) {
                $q->with(['category', 'reporter', 'validator'])->latest('violation_date');
            },
            'counselingRecords' => function ($q) {
                $q->with('counselor')->latest('counseling_date');
            },
        ]);

        return view('bk.students.show', compact('student'));
    }
}
