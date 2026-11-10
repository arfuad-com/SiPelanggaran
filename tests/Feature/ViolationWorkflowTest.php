<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\StudentViolation;
use App\Models\User;
use App\Models\ViolationCategory;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViolationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_teacher_can_report_violation_with_pending_status(): void
    {
        $teacher = User::where('role', 'guru')->first();
        $student = Student::first();
        $category = ViolationCategory::first();

        $initialPoints = $student->total_violation_points;

        $response = $this->actingAs($teacher)->post('/guru/violations', [
            'student_id' => $student->id,
            'violation_category_id' => $category->id,
            'violation_date' => now()->format('Y-m-d'),
            'notes' => 'Catatan laporan testing oleh guru biasa.',
        ]);

        $response->assertRedirect('/guru/violations/my-reports');

        $this->assertDatabaseHas('student_violations', [
            'student_id' => $student->id,
            'violation_category_id' => $category->id,
            'reported_by' => $teacher->id,
            'status' => 'pending',
            'validated_by' => null,
        ]);

        // Poin siswa belum bertambah saat masih pending
        $this->assertEquals($initialPoints, $student->fresh()->total_violation_points);
    }

    public function test_counselor_can_validate_violation_and_accumulate_points(): void
    {
        $counselor = User::where('role', 'bk')->first();
        $teacher = User::where('role', 'guru')->first();
        $student = Student::first();
        $category = ViolationCategory::first();

        $initialPoints = $student->total_violation_points;

        $pendingViolation = StudentViolation::create([
            'student_id' => $student->id,
            'violation_category_id' => $category->id,
            'reported_by' => $teacher->id,
            'violation_date' => now(),
            'notes' => 'Uji validasi konselor BK',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($counselor)->post("/bk/validations/{$pendingViolation->id}", [
            'action' => 'accept',
        ]);

        $response->assertRedirect('/bk/validations');

        $this->assertDatabaseHas('student_violations', [
            'id' => $pendingViolation->id,
            'status' => 'validated',
            'validated_by' => $counselor->id,
        ]);

        // Poin sanksi harus otomatis bertambah sejumlah poin kategori
        $this->assertEquals($initialPoints + $category->points, $student->fresh()->total_violation_points);
    }
}
