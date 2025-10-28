<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CleanAndSeedUsers extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear all existing users
        User::query()->delete();

        // Get roles (assuming they already exist from RoleSeeder)
        $adminRole = Role::where('name', 'admin')->first();
        $guruRole = Role::where('name', 'guru')->first();
        $siswaRole = Role::where('name', 'siswa')->first();
        $orangtuaRole = Role::where('name', 'orang_tua')->first();

        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@sman1donggo.sch.id',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        if ($adminRole) {
            $admin->assignRole($adminRole);
        }

        // Create teacher user
        $guru = User::create([
            'name' => 'Guru',
            'email' => 'guru@sman1donggo.sch.id',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        if ($guruRole) {
            $guru->assignRole($guruRole);
        }

        // Create student user
        $siswa = User::create([
            'name' => 'Siswa',
            'email' => 'siswa@sman1donggo.sch.id',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        if ($siswaRole) {
            $siswa->assignRole($siswaRole);
        }

        // Create parent user
        $ortu = User::create([
            'name' => 'Orang Tua',
            'email' => 'ortu@sman1donggo.sch.id',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        if ($orangtuaRole) {
            $ortu->assignRole($orangtuaRole);
        }

        $this->command->info('✅ Users berhasil dibuat:');
        $this->command->info('   Admin: admin@sman1donggo.sch.id');
        $this->command->info('   Guru: guru@sman1donggo.sch.id');
        $this->command->info('   Siswa: siswa@sman1donggo.sch.id');
        $this->command->info('   Orang Tua: ortu@sman1donggo.sch.id');
        $this->command->info('   Password untuk semua: password');
    }
}
