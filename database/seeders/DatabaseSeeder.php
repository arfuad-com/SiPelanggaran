<?php

namespace Database\Seeders;

use App\Models\CounselingRecord;
use App\Models\Major;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentViolation;
use App\Models\User;
use App\Models\ViolationCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Pengguna (Admin, BK, Guru)
        $admin = User::create([
            'nip' => 'ADMIN001',
            'name' => 'Administrator Utama',
            'email' => 'admin@sekolah.sch.id',
            'phone' => '081211223344',
            'gender' => 'L',
            'position' => 'Kepala Tata Usaha & IT Administrator',
            'domisili' => 'Jl. Diponegoro No. 12, Kel. Citarum, Kec. Bandung Wetan, Kota Bandung',
            'avatar' => 'images/avatars/admin.jpg',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $bk = User::create([
            'nip' => '198501012010011001',
            'name' => 'Dra. Hj. Siti Rahmawati, M.Pd',
            'email' => 'siti.bk@sekolah.sch.id',
            'phone' => '081322334455',
            'gender' => 'P',
            'position' => 'Koordinator Bimbingan & Konseling (BK)',
            'domisili' => 'Komplek Antapani Indah Blok B-3 No. 18, Kec. Antapani, Kota Bandung',
            'avatar' => 'images/avatars/bk.jpg',
            'password' => Hash::make('password123'),
            'role' => 'bk',
            'is_active' => true,
        ]);

        $guru1 = User::create([
            'nip' => '199002022015021002',
            'name' => 'Budi Santoso, S.Pd',
            'email' => 'budi.guru@sekolah.sch.id',
            'phone' => '081298765432',
            'gender' => 'L',
            'position' => 'Wali Kelas X RPL 1 & Guru Rekayasa Perangkat Lunak',
            'domisili' => 'Jl. Soekarno-Hatta No. 240, Kel. Batununggal, Kota Bandung',
            'avatar' => 'images/avatars/guru1.jpg',
            'password' => Hash::make('password123'),
            'role' => 'guru',
            'is_active' => true,
        ]);

        $guru2 = User::create([
            'nip' => '199203032018032003',
            'name' => 'Dewi Lestari, S.Pd',
            'email' => 'dewi.guru@sekolah.sch.id',
            'phone' => '081387654321',
            'gender' => 'P',
            'position' => 'Wali Kelas XI RPL 2 & Guru Desain Multimedia',
            'domisili' => 'Jl. Buah Batu Regency No. C-14, Kec. Lengkong, Kota Bandung',
            'avatar' => 'images/avatars/guru2.jpg',
            'password' => Hash::make('password123'),
            'role' => 'guru',
            'is_active' => true,
        ]);

        // 2. Data Jurusan
        $rpl = Major::create(['name' => 'Rekayasa Perangkat Lunak', 'code' => 'RPL']);
        $tkj = Major::create(['name' => 'Teknik Komputer & Jaringan', 'code' => 'TKJ']);
        $mm = Major::create(['name' => 'Desain Komunikasi Visual / MM', 'code' => 'DKV']);

        // 3. Data Kelas
        $kelas1 = SchoolClass::create(['major_id' => $rpl->id, 'name' => 'X RPL 1']);
        $kelas2 = SchoolClass::create(['major_id' => $rpl->id, 'name' => 'XI RPL 2']);
        $kelas3 = SchoolClass::create(['major_id' => $tkj->id, 'name' => 'X TKJ 1']);
        $kelas4 = SchoolClass::create(['major_id' => $mm->id, 'name' => 'XII DKV 1']);

        // 4. Kategori Pelanggaran (dengan Bobot Poin Sanksi)
        $kategori1 = ViolationCategory::create([
            'name' => 'Terlambat Masuk Sekolah (>15 Menit)',
            'description' => 'Datang ke sekolah setelah bel berbunyi tanpa alasan sah.',
            'points' => 5,
        ]);
        $kategori2 = ViolationCategory::create([
            'name' => 'Atribut Seragam Tidak Lengkap / Tidak Sesuai Hari',
            'description' => 'Tidak memakai dasi, sabuk, topi, kaos kaki, atau sepatu tidak sesuai aturan.',
            'points' => 10,
        ]);
        $kategori3 = ViolationCategory::create([
            'name' => 'Membolos Jam Pelajaran / Keluar Tanpa Izin',
            'description' => 'Meninggalkan kelas atau area sekolah saat jam belajar berlangsung.',
            'points' => 20,
        ]);
        $kategori4 = ViolationCategory::create([
            'name' => 'Merokok / Membawa Rokok & Vape di Sekolah',
            'description' => 'Membawa atau menggunakan rokok/vape di lingkungan sekolah maupun berseragam.',
            'points' => 40,
        ]);
        $kategori5 = ViolationCategory::create([
            'name' => 'Terlibat Perkelahian / Tawuran Fisik',
            'description' => 'Tindakan kekerasan fisik antar sesama siswa di dalam maupun di luar sekolah.',
            'points' => 75,
        ]);

        // 5. Data Siswa
        $siswa1 = Student::create([
            'school_class_id' => $kelas1->id,
            'nisn' => '0071234561',
            'nis' => '2401',
            'name' => 'Ahmad Fauzan',
            'gender' => 'L',
            'total_violation_points' => 65,
            'parent_name' => 'Hendra Gunawan',
            'parent_phone' => '081234567890',
        ]);

        $siswa2 = Student::create([
            'school_class_id' => $kelas2->id,
            'nisn' => '0071234562',
            'nis' => '2402',
            'name' => 'Rian Pratama',
            'gender' => 'L',
            'total_violation_points' => 45,
            'parent_name' => 'Surya Pratama',
            'parent_phone' => '081234567891',
        ]);

        $siswa3 = Student::create([
            'school_class_id' => $kelas3->id,
            'nisn' => '0071234563',
            'nis' => '2403',
            'name' => 'Siti Aisyah',
            'gender' => 'P',
            'total_violation_points' => 15,
            'parent_name' => 'Mansyur',
            'parent_phone' => '081234567892',
        ]);

        $siswa4 = Student::create([
            'school_class_id' => $kelas4->id,
            'nisn' => '0071234564',
            'nis' => '2404',
            'name' => 'Dimas Arya',
            'gender' => 'L',
            'total_violation_points' => 25,
            'parent_name' => 'Bambang',
            'parent_phone' => '081234567893',
        ]);

        $siswa5 = Student::create([
            'school_class_id' => $kelas1->id,
            'nisn' => '0071234565',
            'nis' => '2405',
            'name' => 'Putri Ayu Wandira',
            'gender' => 'P',
            'total_violation_points' => 0,
            'parent_name' => 'Agus Priyono',
            'parent_phone' => '081234567894',
        ]);

        // 6. Pencatatan Kejadian Pelanggaran Multi-Bulan (Historis & Realistis)
        // Bulan-bulan sebelumnya di tahun berjalan
        $historicalViolations = [
            // 5 bulan lalu
            ['student' => $siswa1, 'cat' => $kategori1, 'date' => now()->subMonths(5)->day(10), 'status' => 'validated', 'reporter' => $guru1, 'notes' => 'Terlambat masuk sekolah.'],
            ['student' => $siswa2, 'cat' => $kategori2, 'date' => now()->subMonths(5)->day(14), 'status' => 'validated', 'reporter' => $guru2, 'notes' => 'Sepatu tidak sesuai ketentuan seragam.'],
            ['student' => $siswa4, 'cat' => $kategori1, 'date' => now()->subMonths(5)->day(22), 'status' => 'validated', 'reporter' => $guru1, 'notes' => 'Terlambat upacara bendera.'],

            // 4 bulan lalu
            ['student' => $siswa3, 'cat' => $kategori2, 'date' => now()->subMonths(4)->day(8), 'status' => 'validated', 'reporter' => $guru2, 'notes' => 'Tidak memakai badge lokasi sekolah.'],
            ['student' => $siswa1, 'cat' => $kategori3, 'date' => now()->subMonths(4)->day(15), 'status' => 'validated', 'reporter' => $guru1, 'notes' => 'Keluar gerbang tanpa izin satpam.'],
            ['student' => $siswa2, 'cat' => $kategori1, 'date' => now()->subMonths(4)->day(19), 'status' => 'validated', 'reporter' => $guru2, 'notes' => 'Terlambat hadir.'],
            ['student' => $siswa4, 'cat' => $kategori3, 'date' => now()->subMonths(4)->day(25), 'status' => 'validated', 'reporter' => $guru1, 'notes' => 'Bolos jam pelajaran matematika.'],

            // 3 bulan lalu
            ['student' => $siswa1, 'cat' => $kategori4, 'date' => now()->subMonths(3)->day(5), 'status' => 'validated', 'reporter' => $guru1, 'notes' => 'Kedapatan membawa pod vape.'],
            ['student' => $siswa2, 'cat' => $kategori3, 'date' => now()->subMonths(3)->day(12), 'status' => 'validated', 'reporter' => $guru2, 'notes' => 'Meninggalkan area sekolah saat istirahat.'],
            ['student' => $siswa3, 'cat' => $kategori1, 'date' => now()->subMonths(3)->day(18), 'status' => 'validated', 'reporter' => $guru2, 'notes' => 'Terlambat 20 menit.'],
            ['student' => $siswa4, 'cat' => $kategori2, 'date' => now()->subMonths(3)->day(26), 'status' => 'validated', 'reporter' => $guru1, 'notes' => 'Seragam tidak rapi.'],

            // 2 bulan lalu
            ['student' => $siswa2, 'cat' => $kategori4, 'date' => now()->subMonths(2)->day(4), 'status' => 'validated', 'reporter' => $guru1, 'notes' => 'Merokok di warung belakang sekolah berseragam.'],
            ['student' => $siswa1, 'cat' => $kategori1, 'date' => now()->subMonths(2)->day(9), 'status' => 'validated', 'reporter' => $guru2, 'notes' => 'Terlambat masuk sekolah.'],
            ['student' => $siswa4, 'cat' => $kategori5, 'date' => now()->subMonths(2)->day(16), 'status' => 'validated', 'reporter' => $bk, 'notes' => 'Terlibat adu fisik dengan siswa kelas sebelah.'],
            ['student' => $siswa3, 'cat' => $kategori2, 'date' => now()->subMonths(2)->day(21), 'status' => 'validated', 'reporter' => $guru2, 'notes' => 'Atribut seragam tidak lengkap.'],
            ['student' => $siswa2, 'cat' => $kategori1, 'date' => now()->subMonths(2)->day(28), 'status' => 'validated', 'reporter' => $guru1, 'notes' => 'Terlambat datang.'],

            // 1 bulan lalu
            ['student' => $siswa1, 'cat' => $kategori3, 'date' => now()->subMonths(1)->day(6), 'status' => 'validated', 'reporter' => $guru1, 'notes' => 'Membolos jam pelajaran produktif.'],
            ['student' => $siswa2, 'cat' => $kategori1, 'date' => now()->subMonths(1)->day(11), 'status' => 'validated', 'reporter' => $guru2, 'notes' => 'Terlambat 15 menit.'],
            ['student' => $siswa3, 'cat' => $kategori1, 'date' => now()->subMonths(1)->day(17), 'status' => 'validated', 'reporter' => $guru1, 'notes' => 'Terlambat masuk.'],
            ['student' => $siswa4, 'cat' => $kategori2, 'date' => now()->subMonths(1)->day(22), 'status' => 'validated', 'reporter' => $guru2, 'notes' => 'Tidak memakai ikat pinggang standar.'],
            ['student' => $siswa1, 'cat' => $kategori2, 'date' => now()->subMonths(1)->day(27), 'status' => 'rejected', 'notes' => 'Laporan ditolak setelah klarifikasi wali kelas, izin dispen.'],

            // Bulan ini (Aktif)
            ['student' => $siswa1, 'cat' => $kategori4, 'date' => now()->subDays(15), 'status' => 'validated', 'reporter' => $guru1, 'notes' => 'Tertangkap basah di belakang kantin membawa rokok elektrik.'],
            ['student' => $siswa1, 'cat' => $kategori3, 'date' => now()->subDays(5), 'status' => 'validated', 'reporter' => $bk, 'notes' => 'Tidak masuk kelas sesi 3 dan 4 tanpa keterangan.'],
            ['student' => $siswa1, 'cat' => $kategori1, 'date' => now()->subDays(2), 'status' => 'validated', 'reporter' => $guru2, 'notes' => 'Terlambat 25 menit karena macet jalanan.'],
            ['student' => $siswa2, 'cat' => $kategori4, 'date' => now()->subDays(7), 'status' => 'validated', 'reporter' => $guru1, 'notes' => 'Membawa rokok di saku jaket saat razia.'],
            ['student' => $siswa2, 'cat' => $kategori1, 'date' => now()->subDays(3), 'status' => 'validated', 'reporter' => $guru2, 'notes' => 'Terlambat tiba pukul 07.25 WIB.'],
            ['student' => $siswa4, 'cat' => $kategori3, 'date' => now()->subDays(1), 'status' => 'pending', 'reporter' => $guru1, 'notes' => 'Siswa terlihat melompati pagar samping saat pergantian jam pelajaran.'],
            ['student' => $siswa3, 'cat' => $kategori2, 'date' => now(), 'status' => 'pending', 'reporter' => $guru2, 'notes' => 'Tidak memakai dasi dan sabuk saat upacara hari Senin.'],
        ];

        foreach ($historicalViolations as $v) {
            StudentViolation::create([
                'student_id' => $v['student']->id,
                'violation_category_id' => $v['cat']->id,
                'reported_by' => $v['reporter']->id ?? $guru1->id,
                'validated_by' => $v['status'] === 'validated' ? $bk->id : null,
                'violation_date' => $v['date'],
                'notes' => $v['notes'],
                'status' => $v['status'],
                'validated_at' => $v['status'] === 'validated' ? $v['date'] : null,
            ]);
        }

        // 7. Sesi Bimbingan Konseling Multi-Bulan (Counseling Records)
        $historicalCounseling = [
            [
                'student_id' => $siswa1->id,
                'counselor_id' => $bk->id,
                'counseling_date' => now()->subMonths(4)->day(17),
                'issue_summary' => 'Siswa sering bolos pada jam pelajaran terakhir.',
                'counseling_result' => 'Siswa berjanji tidak mengulangi dan bersedia dibimbing wali kelas.',
                'follow_up_action' => 'Pemantauan absensi harian.',
            ],
            [
                'student_id' => $siswa4->id,
                'counselor_id' => $bk->id,
                'counseling_date' => now()->subMonths(2)->day(18),
                'issue_summary' => 'Konseling penanganan konflik fisik antar siswa di luar gerbang.',
                'counseling_result' => 'Kedua pihak saling memaafkan dan menandatangani surat damai bermaterai.',
                'follow_up_action' => 'Pemanggilan kedua orang tua dan mediasi damai terpadu.',
            ],
            [
                'student_id' => $siswa2->id,
                'counselor_id' => $bk->id,
                'counseling_date' => now()->subMonths(2)->day(6),
                'issue_summary' => 'Konseling terindikasi merokok di luar sekolah saat jam aktif.',
                'counseling_result' => 'Edukasi bahaya rokok dan komitmen menjaga nama baik sekolah.',
                'follow_up_action' => 'Laporan berkala ke wali murid.',
            ],
            [
                'student_id' => $siswa1->id,
                'counselor_id' => $bk->id,
                'counseling_date' => now()->subDays(12),
                'issue_summary' => 'Siswa memiliki kecenderungan merokok karena pengaruh pergaulan lingkungan tempat tinggal.',
                'counseling_result' => 'Siswa mengakui kesalahannya, bersedia membuat surat perjanjian di hadapan wali kelas.',
                'follow_up_action' => 'Pemanggilan orang tua jika siswa mengulangi perbuatannya dan pemantauan mingguan oleh guru BK.',
            ],
            [
                'student_id' => $siswa3->id,
                'counselor_id' => $bk->id,
                'counseling_date' => now()->subDays(8),
                'issue_summary' => 'Motivasi belajar dan kedisiplinan hadir tepat waktu di kelas.',
                'counseling_result' => 'Siswa menyadari pentingnya kedisiplinan waktu pagi.',
                'follow_up_action' => 'Evaluasi dua pekan ke depan bersama wali kelas.',
            ],
        ];

        foreach ($historicalCounseling as $c) {
            CounselingRecord::create($c);
        }

        // Sinkronisasi akumulasi total_violation_points siswa berdasarkan pelanggaran 'validated'
        foreach (Student::all() as $st) {
            $totalPoints = StudentViolation::where('student_id', $st->id)
                ->where('status', 'validated')
                ->join('violation_categories', 'student_violations.violation_category_id', '=', 'violation_categories.id')
                ->sum('violation_categories.points');

            $st->update(['total_violation_points' => $totalPoints]);
        }
    }
}
