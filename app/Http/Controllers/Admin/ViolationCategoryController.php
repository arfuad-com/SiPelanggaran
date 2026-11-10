<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ViolationCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ViolationCategoryController extends Controller
{
    public function index(): View
    {
        $categories = ViolationCategory::withCount('violations')
            ->orderBy('points')
            ->get();

        return view('admin.violation-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.violation-categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'points' => 'required|integer|min:1|max:500',
            'description' => 'nullable|string',
        ]);

        ViolationCategory::create($validated);

        return redirect()->route('admin.violation-categories.index')
            ->with('success', 'Kategori pelanggaran berhasil ditambahkan.');
    }

    public function edit(ViolationCategory $violationCategory): View
    {
        return view('admin.violation-categories.edit', compact('violationCategory'));
    }

    public function update(Request $request, ViolationCategory $violationCategory): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'points' => 'required|integer|min:1|max:500',
            'description' => 'nullable|string',
        ]);

        $violationCategory->update($validated);

        return redirect()->route('admin.violation-categories.index')
            ->with('success', 'Kategori pelanggaran berhasil diperbarui.');
    }

    public function destroy(ViolationCategory $violationCategory): RedirectResponse
    {
        if ($violationCategory->violations()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena sudah memiliki riwayat pelanggaran tercatat.');
        }

        $violationCategory->delete();

        return redirect()->route('admin.violation-categories.index')
            ->with('success', 'Kategori pelanggaran berhasil dihapus.');
    }
}
