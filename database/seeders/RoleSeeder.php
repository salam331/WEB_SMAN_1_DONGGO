<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $admin = Role::create(['name' => 'admin']);
        $guru = Role::create(['name' => 'guru']);
        $siswa = Role::create(['name' => 'siswa']);
        $orang_tua = Role::create(['name' => 'orang_tua']);

        // Define permissions
        $permissions = [
            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Student management
            'view students',
            'create students',
            'edit students',
            'delete students',

            // Teacher management
            'view teachers',
            'create teachers',
            'edit teachers',
            'delete teachers',

            // Class management
            'view classes',
            'create classes',
            'edit classes',
            'delete classes',

            // Subject management
            'view subjects',
            'create subjects',
            'edit subjects',
            'delete subjects',

            // Schedule management
            'view schedules',
            'create schedules',
            'edit schedules',
            'delete schedules',

            // Attendance management
            'view attendances',
            'create attendances',
            'edit attendances',
            'delete attendances',

            // Exam management
            'view exams',
            'create exams',
            'edit exams',
            'delete exams',

            // Grade management
            'view grades',
            'create grades',
            'edit grades',
            'delete grades',

            // Announcement management
            'view announcements',
            'create announcements',
            'edit announcements',
            'delete announcements',

            // Material management
            'view materials',
            'create materials',
            'edit materials',
            'delete materials',

            // Invoice management
            'view invoices',
            'create invoices',
            'edit invoices',
            'delete invoices',

            // Library management
            'view library',
            'borrow books',
            'return books',

            // Gallery management
            'view galleries',
            'create galleries',
            'edit galleries',
            'delete galleries',

            // Extracurricular management
            'view extracurriculars',
            'create extracurriculars',
            'edit extracurriculars',
            'delete extracurriculars',

            // Message management
            'view messages',
            'send messages',

            // Notification management
            'view notifications',

            // Log management
            'view logs',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $admin->givePermissionTo(Permission::all());

        $guru->givePermissionTo([
            'view students',
            'view classes',
            'view subjects',
            'view schedules',
            'create schedules',
            'edit schedules',
            'view attendances',
            'create attendances',
            'edit attendances',
            'view exams',
            'create exams',
            'edit exams',
            'view grades',
            'create grades',
            'edit grades',
            'view announcements',
            'create announcements',
            'edit announcements',
            'view materials',
            'create materials',
            'edit materials',
            'view messages',
            'send messages',
            'view notifications',
        ]);

        $siswa->givePermissionTo([
            'view schedules',
            'view attendances',
            'view exams',
            'view grades',
            'view announcements',
            'view materials',
            'view invoices',
            'view library',
            'borrow books',
            'view galleries',
            'view extracurriculars',
            'view messages',
            'send messages',
            'view notifications',
        ]);

        $orang_tua->givePermissionTo([
            'view students',
            'view attendances',
            'view grades',
            'view announcements',
            'view invoices',
            'view messages',
            'send messages',
            'view notifications',
        ]);
    }
}
