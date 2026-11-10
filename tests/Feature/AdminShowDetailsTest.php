<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminShowDetailsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_admin_can_view_student_show_page(): void
    {
        $admin = User::where('role', 'admin')->first();
        $student = Student::first();

        $response = $this->actingAs($admin)->get(route('admin.students.show', $student->id));

        $response->assertStatus(200);
        $response->assertSee($student->name);
        $response->assertSee($student->nisn);
    }

    public function test_admin_can_view_user_show_page(): void
    {
        $admin = User::where('role', 'admin')->first();
        $user = User::where('role', 'guru')->first();

        $response = $this->actingAs($admin)->get(route('admin.users.show', $user->id));

        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee($user->nip);
        $response->assertSee($user->domisili);
        $response->assertSee($user->phone);
    }

    public function test_layout_contains_live_realtime_clock_widget(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('live-clock-time');
        $response->assertSee('mobile-live-clock');
        $response->assertSee('updateAppLiveClock');
    }

    public function test_user_can_update_profile_biodata_and_domisili(): void
    {
        $guru = User::where('role', 'guru')->first();

        $response = $this->actingAs($guru)->put(route('profile.information.update'), [
            'name' => 'Guru Terupdate, S.Pd',
            'email' => 'updated.guru@sekolah.sch.id',
            'phone' => '089988776655',
            'gender' => 'L',
            'position' => 'Wali Kelas XII RPL 1',
            'domisili' => 'Jl. Cisitu Lama No. 20, Coblong, Kota Bandung',
        ]);

        $response->assertSessionHas('success');
        $guru->refresh();
        $this->assertEquals('Guru Terupdate, S.Pd', $guru->name);
        $this->assertEquals('089988776655', $guru->phone);
        $this->assertEquals('Jl. Cisitu Lama No. 20, Coblong, Kota Bandung', $guru->domisili);
    }

    public function test_admin_reports_contains_clickable_student_and_reporter_links(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.reports.index'));

        $response->assertStatus(200);
        $response->assertSee('admin/students/');
        $response->assertSee('admin/users/');
        $response->assertSee('openStudentQuickModal');
        $response->assertSee('openUserQuickModal');
    }

    public function test_guru_can_view_create_violation_form_with_submit_button(): void
    {
        $guru = User::where('role', 'guru')->first();

        $response = $this->actingAs($guru)->get(route('guru.violations.create'));

        $response->assertStatus(200);
        $response->assertSee('Kirim Laporan ke Guru BK');
        $response->assertSee('bg-indigo-600');
    }
}
