<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SchoolProfile;

class SchoolProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SchoolProfile::create([
            'name' => 'SMA Negeri 1 Donggo',
            'address' => 'Jl. Raya Donggo, Kabupaten Bima, Nusa Tenggara Barat',
            'phone' => '(0374) 123456',
            'email' => 'info@sman1donggo.sch.id',
            'headmaster_name' => 'Drs. Ahmad Yani, M.Pd.',
            'vision' => 'Menjadi sekolah unggul dalam bidang akademik dan non-akademik yang berlandaskan iman dan taqwa.',
            'mission' => '1. Meningkatkan kualitas pembelajaran dan bimbingan siswa.\n2. Mengembangkan potensi siswa secara holistik.\n3. Meningkatkan kompetensi guru dan tenaga kependidikan.\n4. Meningkatkan sarana dan prasarana pendidikan.',
            'logo_path' => 'logos/sman1donggo.png',
            'map_embed' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3945.123456789012!2d118.56789012345678!3d-8.567890123456789!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zOMKwMzQnMDQuNCJTIDExOMKwMzQnMDQuNCJF!5e0!3m2!1sen!2sid!4v1234567890123!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
            'hero_title' => 'Tentang {{ $school->name ?? "SMAN 1 Donggo" }}',
            'hero_description' => 'Membangun Generasi Unggul dengan Pendidikan Berkualitas',
            'school_description' => 'SMA Negeri 1 Donggo adalah sekolah menengah atas yang berkomitmen untuk memberikan pendidikan berkualitas tinggi kepada siswa-siswinya. Dengan tenaga pengajar yang profesional dan fasilitas yang memadai, kami berusaha menciptakan lingkungan belajar yang kondusif dan inspiratif.',
            'features' => json_encode([
                ['icon' => 'fa-graduation-cap', 'color' => 'primary', 'title' => 'Kurikulum Modern', 'desc' => 'Kurikulum terkini yang disesuaikan dengan perkembangan zaman.'],
                ['icon' => 'fa-users', 'color' => 'success', 'title' => 'Guru Berkompeten', 'desc' => 'Tenaga pengajar profesional dan berpengalaman.'],
                ['icon' => 'fa-flask', 'color' => 'info', 'title' => 'Fasilitas Lengkap', 'desc' => 'Sarana memadai untuk mendukung pembelajaran.'],
                ['icon' => 'fa-trophy', 'color' => 'warning', 'title' => 'Prestasi Gemilang', 'desc' => 'Prestasi akademik & non-akademik.'],
                ['icon' => 'fa-handshake', 'color' => 'danger', 'title' => 'Kerjasama', 'desc' => 'Bermitra dengan banyak institusi.'],
                ['icon' => 'fa-heart', 'color' => 'secondary', 'title' => 'Nilai Karakter', 'desc' => 'Menanamkan pendidikan karakter kuat.']
            ]),
            'statistics' => json_encode([
                ['label' => 'Siswa Aktif', 'value' => '500+'],
                ['label' => 'Guru & Staff', 'value' => '50+'],
                ['label' => 'Kelas', 'value' => '24'],
                ['label' => 'Prestasi', 'value' => '100+']
            ]),
        ]);
    }
}
