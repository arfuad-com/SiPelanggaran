<?php

namespace App\Http\Controllers\BK;

use App\Http\Controllers\Controller;
use App\Models\CounselingRecord;
use App\Models\SchoolClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CounselingController extends Controller
{
    public function index(Request $request): View
    {
        $query = CounselingRecord::with(['student.schoolClass', 'counselor']);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $counselings = $query->latest('counseling_date')->paginate(10)->withQueryString();

        return view('bk.counseling.index', compact('counselings'));
    }

    public function create(Request $request): View
    {
        $classes = SchoolClass::with('students')->orderBy('name')->get();
        $selectedStudentId = $request->query('student_id');

        return view('bk.counseling.create', compact('classes', 'selectedStudentId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'counseling_date' => 'required|date',
            'issue_summary' => 'required|string',
            'counseling_result' => 'required|string',
            'follow_up_action' => 'required|string',
        ]);

        CounselingRecord::create([
            'student_id' => $validated['student_id'],
            'counselor_id' => Auth::id(),
            'counseling_date' => $validated['counseling_date'],
            'issue_summary' => $validated['issue_summary'],
            'counseling_result' => $validated['counseling_result'],
            'follow_up_action' => $validated['follow_up_action'],
        ]);

        return redirect()->route('bk.counseling.index')
            ->with('success', 'Sesi bimbingan konseling berhasil dicatat.');
    }

    public function show(CounselingRecord $counseling): View
    {
        $counseling->load(['student.schoolClass', 'counselor']);

        return view('bk.counseling.show', compact('counseling'));
    }
}
