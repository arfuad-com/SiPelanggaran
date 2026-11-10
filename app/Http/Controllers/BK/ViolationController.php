<?php

namespace App\Http\Controllers\BK;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentViolation;
use App\Models\ViolationCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ViolationController extends Controller
{
    public function create(): View
    {
        $classes = SchoolClass::with('students')->orderBy('name')->get();
        $categories = ViolationCategory::orderBy('points')->get();

        return view('bk.violations.create', compact('classes', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'violation_category_id' => 'required|exists:violation_categories,id',
            'violation_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        return DB::transaction(function () use ($validated) {
            $student = Student::findOrFail($validated['student_id']);
            $category = ViolationCategory::findOrFail($validated['violation_category_id']);

            StudentViolation::create([
                'student_id' => $student->id,
                'violation_category_id' => $category->id,
                'reported_by' => Auth::id(),
                'validated_by' => Auth::id(),
                'violation_date' => $validated['violation_date'],
                'notes' => $validated['notes'],
                'status' => 'validated', // Otomatis validated jika BK yang mencatat
                'validated_at' => now(),
            ]);

            // Tambahkan poin sanksi langsung
            $student->increment('total_violation_points', $category->points);

            return redirect()->route('bk.students.show', $student->id)
                ->with('success', "Pelanggaran berhasil dicatat ke Buku Kasus dan divalidasi. {$category->points} poin telah ditambahkan ke {$student->name}.");
        });
    }
}
