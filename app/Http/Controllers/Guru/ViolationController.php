<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\StudentViolation;
use App\Models\ViolationCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ViolationController extends Controller
{
    public function create(): View
    {
        $classes = SchoolClass::with('students')->orderBy('name')->get();
        $categories = ViolationCategory::orderBy('points')->get();

        return view('guru.violations.create', compact('classes', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'violation_category_id' => 'required|exists:violation_categories,id',
            'violation_date' => 'required|date',
            'notes' => 'required|string|max:1000',
        ], [
            'notes.required' => 'Catatan kejadian/kronologi wajib diisi untuk bukti laporan.',
        ]);

        StudentViolation::create([
            'student_id' => $validated['student_id'],
            'violation_category_id' => $validated['violation_category_id'],
            'reported_by' => Auth::id(),
            'validated_by' => null,
            'violation_date' => $validated['violation_date'],
            'notes' => $validated['notes'],
            'status' => 'pending', // Wajib pending
            'validated_at' => null,
        ]);

        return redirect()->route('guru.violations.my-reports')
            ->with('success', 'Laporan pelanggaran berhasil dikirim ke Guru BK dengan status Menunggu Validasi (Pending).');
    }
}
