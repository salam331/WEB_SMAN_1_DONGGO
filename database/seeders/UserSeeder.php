<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@sman1donggo.sch.id',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Create teacher user
        $teacher = User::create([
            'name' => 'Guru Bahasa Indonesia',
            'email' => 'guru@sman1donggo.sch.id',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $teacher->assignRole('guru');

        // Create student user
        $student = User::create([
            'name' => 'Siswa Contoh',
            'email' => 'siswa@sman1donggo.sch.id',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $student->assignRole('siswa');

        // Create parent user
        $parent = User::create([
            'name' => 'Orang Tua Contoh',
            'email' => 'ortu@sman1donggo.sch.id',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $parent->assignRole('orang_tua');
    }
}
