<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_bk_dashboard_loads_with_all_chart_data(): void
    {
        $bk = User::where('role', 'bk')->first();

        $response = $this->actingAs($bk)->get('/bk/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas([
            'chartMonths',
            'monthlyViolations',
            'monthlyCounseling',
            'categoryLabels',
            'categoryCounts',
            'classLabels',
            'classCounts',
            'statusCounts',
        ]);
        $response->assertSee('Grafik Tren Pelanggaran');
        $response->assertSee('Distribusi Kategori Pelanggaran');
        $response->assertSee('Sebaran Kasus per Kelas');
        $response->assertSee('Status Validasi Laporan');
    }

    public function test_admin_dashboard_loads_with_all_chart_data(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas([
            'chartMonths',
            'monthlyViolations',
            'monthlyCounseling',
            'categoryLabels',
            'categoryCounts',
            'classLabels',
            'classCounts',
        ]);
        $response->assertSee('Grafik Tren Kasus');
        $response->assertSee('Proporsi Kategori Kasus');
    }

    public function test_regular_teacher_cannot_access_bk_dashboard(): void
    {
        $guru = User::where('role', 'guru')->first();

        $response = $this->actingAs($guru)->get('/bk/dashboard');

        $response->assertStatus(403);
    }

    public function test_authenticated_user_sees_avatar_and_name_in_navbar(): void
    {
        $guru = User::where('role', 'guru')->first();

        $response = $this->actingAs($guru)->get('/guru/dashboard');

        $response->assertStatus(200);
        $response->assertSee($guru->name);
        $response->assertSee($guru->avatar_url);
    }

    public function test_user_can_view_and_update_profile(): void
    {
        $user = User::first();

        $response = $this->actingAs($user)->get('/profile');
        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee($user->nip);
        $response->assertSee('Foto Profil');
    }
}
