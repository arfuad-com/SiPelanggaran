<?php

namespace App\Http\Controllers\BK;

use App\Http\Controllers\Controller;
use App\Models\StudentViolation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ValidationController extends Controller
{
    public function index(): View
    {
        $pendingViolations = StudentViolation::with(['student.schoolClass', 'category', 'reporter'])
            ->where('status', 'pending')
            ->latest('created_at')
            ->paginate(15);

        return view('bk.validations.index', compact('pendingViolations'));
    }

    public function process(Request $request, StudentViolation $violation): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:accept,reject',
        ]);

        if ($violation->status !== 'pending') {
            return back()->with('error', 'Laporan ini sudah diproses sebelumnya.');
        }

        return DB::transaction(function () use ($request, $violation) {
            $student = $violation->student;
            $points = $violation->category->points;

            if ($request->action === 'accept') {
                $violation->update([
                    'status' => 'validated',
                    'validated_by' => Auth::id(),
                    'validated_at' => now(),
                ]);

                // Akumulasikan poin ke siswa
                $student->increment('total_violation_points', $points);

                return redirect()->route('bk.validations.index')
                    ->with('success', "Laporan berhasil DISETUJUI. Sebanyak {$points} poin telah diakumulasikan ke {$student->name}. Total poin sekarang: {$student->fresh()->total_violation_points}.");
            } else {
                $violation->update([
                    'status' => 'rejected',
                    'validated_by' => Auth::id(),
                    'validated_at' => now(),
                ]);

                return redirect()->route('bk.validations.index')
                    ->with('info', "Laporan pelanggaran untuk {$student->name} telah DITOLAK.");
            }
        });
    }
}
