<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\SchoolClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SchoolClassController extends Controller
{
    public function index(): View
    {
        $majors = Major::withCount('classes')->get();
        $classes = SchoolClass::with('major')->withCount('students')->orderBy('name')->get();

        return view('admin.classes.index', compact('majors', 'classes'));
    }

    public function storeMajor(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:majors,code',
        ]);

        Major::create($validated);

        return back()->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function destroyMajor(Major $major): RedirectResponse
    {
        if ($major->classes()->count() > 0) {
            return back()->with('error', 'Jurusan tidak dapat dihapus karena masih memiliki kelas terdaftar.');
        }

        $major->delete();

        return back()->with('success', 'Jurusan berhasil dihapus.');
    }

    public function storeClass(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'major_id' => 'required|exists:majors,id',
            'name' => 'required|string|max:50',
        ]);

        SchoolClass::create($validated);

        return back()->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function destroyClass(SchoolClass $class): RedirectResponse
    {
        if ($class->students()->count() > 0) {
            return back()->with('error', 'Kelas tidak dapat dihapus karena masih memiliki data siswa.');
        }

        $class->delete();

        return back()->with('success', 'Kelas berhasil dihapus.');
    }
}
