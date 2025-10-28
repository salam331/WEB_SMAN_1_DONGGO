<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use App\Models\ClassRoom;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class UserStudentClassWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->startSession();
        $this->token = Session::token();
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    }

    /**
     * Test the complete workflow: create user, create student, assign class
     */
    public function test_create_user_student_class_workflow()
    {
        // Create roles if not exists
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $guruRole = Role::firstOrCreate(['name' => 'guru']);
        $siswaRole = Role::firstOrCreate(['name' => 'siswa']);

        // Create admin user for authentication
        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');

        // Step 1: Create a new user
        $userData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'phone' => '081234567890',
            'role' => 'guru',
        ];

        $response = $this->actingAs($adminUser, 'web')
            ->withHeaders(['X-CSRF-TOKEN' => $this->token])
            ->post(route('admin.users.store'), $userData);

        $response->assertRedirect(route('admin.users'));
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);

        $user = User::where('email', 'john.doe@example.com')->first();

        // Step 2: Create a class room
        $classData = [
            'name' => 'XII IPA 1',
            'level' => '12',
            'capacity' => 30,
        ];

        $response = $this->actingAs($adminUser, 'web')
            ->withHeaders(['X-CSRF-TOKEN' => $this->token])
            ->post(route('admin.classes.store'), $classData);

        $response->assertRedirect(route('admin.classes'));
        $this->assertDatabaseHas('class_rooms', [
            'name' => 'XII IPA 1',
            'level' => '12',
            'capacity' => 30,
        ]);

        $classRoom = ClassRoom::where('name', 'XII IPA 1')->first();

        // Step 3: Create a student and assign to the user and class
        $studentData = [
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nis' => '123456789',
            'nisn' => '987654321',
            'birth_place' => 'Jakarta',
            'birth_date' => '2005-05-15',
            'gender' => 'L',
            'class_id' => $classRoom->id,
            'address' => 'Jl. Sudirman No. 123',
        ];

        $response = $this->actingAs($adminUser, 'web')
            ->withHeaders(['X-CSRF-TOKEN' => $this->token])
            ->post(route('admin.students.store'), $studentData);

        $response->assertRedirect(route('admin.students'));
        $this->assertDatabaseHas('students', [
            'nis' => '123456789',
            'nisn' => '987654321',
        ]);

        $student = Student::where('nis', '123456789')->first();
        $studentUser = User::where('email', 'jane.doe@example.com')->first();

        // Verify relationships
        $this->assertEquals($studentUser->id, $student->user_id);
        $this->assertEquals($classRoom->id, $student->rombel_id);
        $this->assertEquals('Jane Doe', $student->user->name);
        $this->assertEquals('XII IPA 1', $student->classRoom->name);

        // Step 4: Verify the complete workflow by checking the student index page
        $response = $this->actingAs($adminUser, 'web')
            ->get(route('admin.students'));

        $response->assertStatus(200);
        $response->assertSee('Jane Doe');
        $response->assertSee('XII IPA 1');
        $response->assertSee('123456789');
    }

    /**
     * Test validation errors in the workflow
     */
    public function test_workflow_validation_errors()
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');

        // Test duplicate email
        $userData = [
            'name' => 'John Doe',
            'email' => $adminUser->email, // Duplicate email
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->actingAs($adminUser, 'web')
            ->withHeaders(['X-CSRF-TOKEN' => $this->token])
            ->post(route('admin.users.store'), $userData);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');

        // Test duplicate NIS
        $user = User::factory()->create();
        $classRoom = ClassRoom::factory()->create();

        $studentData = [
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nis' => '123456789',
            'nisn' => '987654321',
            'birth_place' => 'Jakarta',
            'birth_date' => '2005-05-15',
            'gender' => 'L',
            'class_id' => $classRoom->id,
            'address' => 'Jl. Sudirman No. 123',
        ];

        $this->actingAs($adminUser, 'web')
            ->withHeaders(['X-CSRF-TOKEN' => $this->token])
            ->post(route('admin.students.store'), $studentData);

        // Try to create another student with same NIS
        $user2 = User::factory()->create();
        $studentData['email'] = 'jane2.doe@example.com';

        $response = $this->actingAs($adminUser, 'web')
            ->post(route('admin.students.store'), $studentData);

        $response->assertRedirect();
        $response->assertSessionHasErrors('nis');
    }

    /**
     * Test authorization - non-admin cannot create users/students
     */
    public function test_authorization_restrictions()
    {
        $studentRole = Role::firstOrCreate(['name' => 'siswa']);
        $regularUser = User::factory()->create();
        $regularUser->assignRole('siswa');

        $userData = [
            'name' => 'Unauthorized User',
            'email' => 'unauthorized@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Non-admin should not be able to create users
        $response = $this->actingAs($regularUser, 'web')
            ->withHeaders(['X-CSRF-TOKEN' => $this->token])
            ->post(route('admin.users.store'), $userData);

        $response->assertForbidden();

        // Non-admin should not be able to create students
        $studentData = [
            'name' => 'Unauthorized Student',
            'email' => 'unauthorized.student@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nis' => '123456789',
            'nisn' => '987654321',
            'birth_place' => 'Jakarta',
            'birth_date' => '2005-05-15',
            'gender' => 'L',
            'class_id' => 1,
            'address' => 'Jl. Sudirman No. 123',
        ];

        $response = $this->actingAs($regularUser, 'web')
            ->withHeaders(['X-CSRF-TOKEN' => $this->token])
            ->post(route('admin.students.store'), $studentData);

        $response->assertForbidden();
    }
}
