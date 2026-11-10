<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\StudentViolation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MyReportController extends Controller
{
    public function index(Request $request): View
    {
        $query = StudentViolation::with(['student.schoolClass', 'category', 'validator'])
            ->where('reported_by', Auth::id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->latest('created_at')->paginate(12)->withQueryString();

        return view('guru.violations.my-reports', compact('reports'));
    }
}
