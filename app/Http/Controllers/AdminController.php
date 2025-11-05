<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\ParentModel;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\Announcement;
use App\Models\Material;
use App\Models\Invoice;
use App\Models\Attendance;
use App\Models\Log;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoicesExport;
use App\Exports\AttendancesExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\LogService;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_teachers' => Teacher::count(),
            'total_students' => Student::count(),
            'total_classes' => ClassRoom::count(),
            'total_subjects' => Subject::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // User Management
    public function users()
    {
        $users = User::with('roles')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'is_active' => true,
        ]);

        $user->assignRole($request->role);

        // Create related profile depending on role
        if ($request->role === 'guru') {
            // Generate unique NIP for teacher
            $nip = 'NIP' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

            Teacher::create([
                'user_id' => $user->id,
                'nip' => $nip,
                'jabatan' => 'Guru',
                'kelas_wali_id' => null,
                'bio' => null,
            ]);
        } elseif ($request->role === 'orang_tua' || $request->role === 'ortu' || $request->role === 'parent') {
            ParentModel::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'phone' => $request->phone,
                'relation_to_student' => 'Ayah/Ibu', // Default value
            ]);
        }

        LogService::logCreate('User', $user->id, $user->toArray(), "Created user: {$user->name} with role: {$request->role}");

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function editUser(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|string|exists:roles,name',
            'is_active' => 'required|boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => $request->is_active,
        ]);

        if (!empty($request->password)) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Update role
        $user->syncRoles([$request->role]);

        LogService::logUpdate('User', $user->id, $user->getOriginal(), $user->toArray(), "Updated user: {$user->name}");

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    public function destroyUser(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'Cannot delete your own account.');
        }

        // Remove roles
        $user->syncRoles([]);

        // Delete related profiles
        if ($user->hasRole('siswa')) {
            $user->student()->delete();
        } elseif ($user->hasRole('guru')) {
            $user->teacher()->delete();
        } elseif ($user->hasRole('orang_tua')) {
            $user->parent()->delete();
        }

        LogService::logDelete('User', $user->id, $user->toArray(), "Deleted user: {$user->name}");

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

    // Teacher Management
    public function teachers(Request $request)
    {
        $query = Teacher::with('user');

        // Search functionality
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nip', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $teachers = $query->paginate(20)->withQueryString();
        return view('admin.teachers.index', compact('teachers'));
    }

    // Show create form for teacher
    public function createTeacher()
    {
        return view('admin.teachers.create');
    }

    // Store new teacher (creates user + teacher profile)
    public function storeTeacher(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'nip' => 'nullable|string|unique:teachers,nip',
            'jabatan' => 'nullable|string|max:255',
        ]);

        // create user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => true,
        ]);

        // assign role 'guru' (assumes role exists)
        if (Role::where('name', 'guru')->exists()) {
            $user->assignRole('guru');
        }

        // create teacher profile
        $teacher = Teacher::create([
            'user_id' => $user->id,
            'nip' => $data['nip'] ?? null,
            'jabatan' => $data['jabatan'] ?? null,
        ]);

        return redirect()->route('admin.teachers')->with('success', 'Guru berhasil ditambahkan.');
    }

    // Edit teacher
    public function editTeacher(Teacher $teacher)
    {
        $teacher->load('user');
        return view('admin.teachers.edit', compact('teacher'));
    }

    // Update teacher
    public function updateTeacher(Request $request, Teacher $teacher)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($teacher->user_id)],
            'nip' => ['nullable', Rule::unique('teachers', 'nip')->ignore($teacher->id)],
            'jabatan' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // update user
        $teacher->user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (!empty($data['password'])) {
            $teacher->user->update(['password' => Hash::make($data['password'])]);
        }

        // update teacher profile
        $teacher->update([
            'nip' => $data['nip'] ?? $teacher->nip,
            'jabatan' => $data['jabatan'] ?? $teacher->jabatan,
        ]);

        return redirect()->route('admin.teachers')->with('success', 'Data guru diperbarui.');
    }

    // Delete teacher (and user via cascade)
    public function destroyTeacher(Teacher $teacher)
    {
        $teacher->delete();
        return redirect()->route('admin.teachers')->with('success', 'Guru dihapus.');
    }

    // Student Management
    public function students()
    {
        $students = Student::with('user', 'classRoom')->paginate(20);
        return view('admin.students.index', compact('students'));
    }

    // Class Management
    public function classes()
    {
        $classes = ClassRoom::with('homeroomTeacher.user')->paginate(20);
        return view('admin.classes.index', compact('classes'));
    }

    // Subject Management
    public function subjects()
    {
        $subjects = Subject::paginate(20);
        return view('admin.subjects.index', compact('subjects'));
    }

    // Schedule Management
    public function schedulesIndex(Request $request)
    {
        $query = Schedule::with('classRoom', 'subject', 'teacher.user');

        // Search functionality
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('subject', function($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%")
                       ->orWhere('code', 'like', "%{$search}%");
                })
                ->orWhereHas('teacher.user', function($tq) use ($search) {
                    $tq->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('classRoom', function($cq) use ($search) {
                    $cq->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Filter by day
        if ($request->day) {
            $query->where('day', $request->day);
        }

        $schedules = $query->orderBy('day')
                          ->orderBy('start_time')
                          ->paginate(20)
                          ->withQueryString();

        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        return view('admin.schedules.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'class_id' => ['required', 'exists:class_rooms,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'material_id' => ['nullable', 'exists:materials,id'],
            'day' => ['required', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'room' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            Schedule::create($data);

            return redirect()
                ->route('admin.schedules.index')
                ->with('success', 'Jadwal pelajaran berhasil dibuat.');

        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database constraint violations (unique constraints, etc.)
            if ($e->getCode() == 23000) { // Integrity constraint violation
                return back()
                    ->withInput()
                    ->with('error', 'Jadwal dengan kombinasi kelas, mata pelajaran, dan guru yang sama sudah ada pada hari dan waktu tersebut.');
            }

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan database. Silakan coba lagi.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat jadwal pelajaran. Silakan coba lagi.');
        }
    }

    public function show(Schedule $schedule)
    {
        $schedule->load(['classRoom', 'subject', 'teacher.user', 'attendances.student.user']);

        return view('admin.schedules.show', compact('schedule'));
    }

    public function edit(Schedule $schedule)
    {
        return view('admin.schedules.edit', compact('schedule'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $data = $request->validate([
            'class_id' => ['required', 'exists:class_rooms,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'material_id' => ['nullable', 'exists:materials,id'],
            'day' => ['required', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'room' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $schedule->update($data);

            return redirect()
                ->route('admin.schedules.index')
                ->with('success', 'Jadwal pelajaran berhasil diperbarui.');

        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database constraint violations (unique constraints, etc.)
            if ($e->getCode() == 23000) { // Integrity constraint violation
                return back()
                    ->withInput()
                    ->with('error', 'Jadwal dengan kombinasi kelas, mata pelajaran, dan guru yang sama sudah ada pada hari dan waktu tersebut.');
            }

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan database. Silakan coba lagi.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui jadwal pelajaran. Silakan coba lagi.');
        }
    }

    public function destroy(Schedule $schedule)
    {
        try {
            // Check if schedule has related attendance data
            if ($schedule->attendances()->exists()) {
                return back()->with('error',
                    'Tidak dapat menghapus jadwal karena memiliki data kehadiran terkait. Hapus data kehadiran terlebih dahulu.');
            }

            $schedule->delete();
            return redirect()
                ->route('admin.schedules.index')
                ->with('success', 'Jadwal pelajaran berhasil dihapus.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus jadwal pelajaran. Silakan coba lagi.');
        }
    }

    // Announcement Management
    public function announcements()
    {
        $announcements = Announcement::with('postedBy')->paginate(20);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function createAnnouncement()
    {
        return view('admin.announcements.create');
    }

    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'publish_to_all' => 'nullable|boolean',
            'target_audience' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('announcements', 'public');
        }

        // If publish_to_all is checked, set is_published to true and target_audience to 'all'
        // If not checked but target_audience is selected, set is_published to true
        $isPublished = ($request->publish_to_all || $request->target_audience) ? true : false;
        $targetAudience = $request->publish_to_all ? 'all' : $request->target_audience;

        Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'posted_by' => auth()->id(),
            'is_public' => $request->publish_to_all ?? false,
            'is_published' => $isPublished,
            'target_audience' => $targetAudience,
            'attachment' => $attachmentPath,
        ]);

        return redirect()->route('admin.announcements')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function editAnnouncement(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function updateAnnouncement(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'publish_to_all' => 'nullable|boolean',
            'target_audience' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        $attachmentPath = $announcement->attachment;
        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($attachmentPath && \Storage::disk('public')->exists($attachmentPath)) {
                \Storage::disk('public')->delete($attachmentPath);
            }
            $attachmentPath = $request->file('attachment')->store('announcements', 'public');
        }

        // If publish_to_all is checked, set is_published to true and target_audience to 'all'
        // If not checked but target_audience is selected, set is_published to true
        $isPublished = ($request->publish_to_all || $request->target_audience) ? true : false;
        $targetAudience = $request->publish_to_all ? 'all' : $request->target_audience;

        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
            'is_public' => $request->publish_to_all ?? false,
            'is_published' => $isPublished,
            'target_audience' => $targetAudience,
            'attachment' => $attachmentPath,
        ]);

        return redirect()->route('admin.announcements')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroyAnnouncement(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('admin.announcements')->with('success', 'Pengumuman berhasil dihapus.');
    }

    // Material Management
    public function materials()
    {
        $materials = Material::with('subject', 'teacher.user', 'classRoom')->paginate(20);
        return view('admin.materials.index', compact('materials'));
    }

    // Attendance Management
    public function attendancesIndex(Request $request)
    {
        $query = Attendance::with('student.user', 'student.classRoom', 'recordedBy');

        if ($request->filled('class_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('rombel_id', $request->class_id);
            });
        }

        if ($request->filled('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(20);
        return view('admin.attendances.index', compact('attendances'));
    }

    public function createAttendance()
    {
        $students = Student::with('user', 'classRoom')->get();
        $schedules = Schedule::with('subject', 'classRoom', 'teacher.user')->get();
        return view('admin.attendances.create', compact('students', 'schedules'));
    }

    public function storeAttendance(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'schedule_id' => 'required|exists:schedules,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'remark' => 'nullable|string|max:255',
        ]);

        // Check if attendance already exists for this student, schedule, and date
        $existingAttendance = Attendance::where('student_id', $request->student_id)
            ->where('schedule_id', $request->schedule_id)
            ->where('date', $request->date)
            ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('error', 'Data kehadiran untuk siswa ini pada jadwal dan tanggal yang sama sudah ada.')->withInput();
        }

        Attendance::create([
            'student_id' => $request->student_id,
            'schedule_id' => $request->schedule_id,
            'date' => $request->date,
            'status' => $request->status,
            'remark' => $request->remark,
            'recorded_by' => auth()->id(),
        ]);

        LogService::logCreate('Attendance', null, [], "Created attendance record for student ID: {$request->student_id}");

        return redirect()->route('admin.attendances.index')->with('success', 'Data kehadiran berhasil ditambahkan.');
    }

    public function showAttendance(Attendance $attendance)
    {
        $attendance->load('student.user', 'student.classRoom', 'schedule.subject', 'schedule.teacher.user', 'recordedBy');
        return view('admin.attendances.show', compact('attendance'));
    }

    public function editAttendance(Attendance $attendance)
    {
        $attendance->load('student.user', 'student.classRoom', 'schedule.subject', 'schedule.teacher.user');
        $students = Student::with('user', 'classRoom')->get();
        $schedules = Schedule::with('subject', 'classRoom', 'teacher.user')->get();
        return view('admin.attendances.edit', compact('attendance', 'students', 'schedules'));
    }

    public function updateAttendance(Request $request, Attendance $attendance)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'schedule_id' => 'required|exists:schedules,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'remark' => 'nullable|string|max:255',
        ]);

        // Check if another attendance exists for this student, schedule, and date (excluding current record)
        $existingAttendance = Attendance::where('student_id', $request->student_id)
            ->where('schedule_id', $request->schedule_id)
            ->where('date', $request->date)
            ->where('id', '!=', $attendance->id)
            ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('error', 'Data kehadiran untuk siswa ini pada jadwal dan tanggal yang sama sudah ada.')->withInput();
        }

        $oldData = $attendance->toArray();

        $attendance->update([
            'student_id' => $request->student_id,
            'schedule_id' => $request->schedule_id,
            'date' => $request->date,
            'status' => $request->status,
            'remark' => $request->remark,
        ]);

        LogService::logUpdate('Attendance', $attendance->id, $oldData, $attendance->toArray(), "Updated attendance record for student ID: {$request->student_id}");

        return redirect()->route('admin.attendances.index')->with('success', 'Data kehadiran berhasil diperbarui.');
    }

    public function destroyAttendance(Attendance $attendance)
    {
        $oldData = $attendance->toArray();

        $attendance->delete();

        LogService::logDelete('Attendance', $attendance->id, $oldData, "Deleted attendance record for student ID: {$attendance->student_id}");

        return redirect()->route('admin.attendances.index')->with('success', 'Data kehadiran berhasil dihapus.');
    }

    public function exportAttendances(Request $request)
    {
        return Excel::download(new AttendancesExport($request), 'data_kehadiran_' . now()->format('Y-m-d_H-i-s') . '.xlsx');
    }

    public function attendanceReport(Request $request)
    {
        $query = Attendance::with('student.user', 'student.classRoom', 'recordedBy');

        if ($request->filled('class_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('rombel_id', $request->class_id);
            });
        }

        if ($request->filled('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        // Calculate summary statistics
        $totalStudents = $attendances->unique('student_id')->count();
        $totalPresent = $attendances->where('status', 'present')->count();
        $totalAbsent = $attendances->where('status', 'absent')->count();
        $totalLate = $attendances->where('status', 'late')->count();
        $totalExcused = $attendances->where('status', 'excused')->count();

        $pdf = Pdf::loadView('admin.reports.attendance', compact(
            'attendances',
            'totalStudents',
            'totalPresent',
            'totalAbsent',
            'totalLate',
            'totalExcused',
            'request'
        ));

        return $pdf->download('laporan_kehadiran_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    // Attendance Summary
    public function attendanceSummary(Request $request)
    {
        $query = Attendance::with('student.user', 'student.classRoom', 'schedule.subject');

        if ($request->filled('class_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('rombel_id', $request->class_id);
            });
        }

        if ($request->filled('subject_id')) {
            $query->whereHas('schedule', function ($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        if ($request->filled('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        $attendances = $query->get();

        // Group by student and subject
        $summary = [];
        foreach ($attendances as $attendance) {
            $studentId = $attendance->student_id;
            $subjectId = $attendance->schedule->subject_id ?? null;

            if (!isset($summary[$studentId])) {
                $summary[$studentId] = [
                    'student' => $attendance->student,
                    'subjects' => []
                ];
            }

            if ($subjectId && !isset($summary[$studentId]['subjects'][$subjectId])) {
                $summary[$studentId]['subjects'][$subjectId] = [
                    'subject' => $attendance->schedule->subject,
                    'total' => 0,
                    'present' => 0,
                    'absent' => 0,
                    'late' => 0,
                    'excused' => 0,
                ];
            }

            if ($subjectId) {
                $summary[$studentId]['subjects'][$subjectId]['total']++;
                $summary[$studentId]['subjects'][$subjectId][$attendance->status]++;
            }
        }

        $classes = ClassRoom::all();
        $subjects = Subject::all();

        return view('admin.attendances.summary', compact('summary', 'classes', 'subjects', 'request'));
    }

    // Invoice Management
    public function invoices(Request $request)
    {
        $query = Invoice::with('student.user', 'student.classRoom', 'createdBy');

        if ($request->filled('class_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('rombel_id', $request->class_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('due_date', $request->month)
                ->whereYear('due_date', $request->year);
        }
        if ($request->filled('month')) {
            $query->whereMonth('due_date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('due_date', $request->year);
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.invoices.index', compact('invoices'));
    }

    public function createInvoice()
    {
        $students = Student::with('user', 'classRoom')->get();
        return view('admin.invoices.create', compact('students'));
    }

    public function storeInvoice(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date|after:today',
        ]);

        Invoice::create([
            'student_id' => $request->student_id,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'status' => 'unpaid',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.invoices')->with('success', 'Tagihan berhasil dibuat.');
    }

    public function editInvoice(Invoice $invoice)
    {
        $students = Student::with('user', 'classRoom')->get();
        return view('admin.invoices.edit', compact('invoice', 'students'));
    }

    public function updateInvoice(Request $request, Invoice $invoice)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:unpaid,paid,partial',
        ]);

        $invoice->update($request->only(['student_id', 'amount', 'due_date', 'status']));

        return redirect()->route('admin.invoices')->with('success', 'Tagihan berhasil diperbarui.');
    }

    public function destroyInvoice(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('admin.invoices')->with('success', 'Tagihan berhasil dihapus.');
    }

    public function markInvoicePaid(Invoice $invoice)
    {
        $invoice->update(['status' => 'paid']);
        return redirect()->back()->with('success', 'Tagihan ditandai sebagai dibayar.');
    }

    public function exportInvoices(Request $request)
    {
        return Excel::download(new InvoicesExport($request), 'data_tagihan_' . now()->format('Y-m-d_H-i-s') . '.xlsx');
    }

    public function invoiceReport(Request $request)
    {
        $query = Invoice::with('student.user', 'student.classRoom', 'createdBy');

        if ($request->filled('class_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('rombel_id', $request->class_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('due_date', $request->month)
                ->whereYear('due_date', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('due_date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('due_date', $request->year);
        }

        $invoices = $query->orderBy('created_at', 'desc')->get();

        // Calculate summary statistics
        $totalInvoices = $invoices->count();
        $totalAmount = $invoices->sum('amount');
        $paidInvoices = $invoices->where('status', 'paid');
        $totalPaid = $paidInvoices->sum('amount');
        $unpaidInvoices = $invoices->where('status', 'unpaid');
        $totalUnpaid = $unpaidInvoices->sum('amount');
        $partialInvoices = $invoices->where('status', 'partial');
        $totalPartial = $partialInvoices->sum('amount');

        $pdf = Pdf::loadView('admin.reports.invoice', compact(
            'invoices',
            'totalInvoices',
            'totalAmount',
            'totalPaid',
            'totalUnpaid',
            'totalPartial',
            'paidInvoices',
            'unpaidInvoices',
            'partialInvoices',
            'request'
        ));

        return $pdf->download('laporan_tagihan_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    // Logs Management
    public function logs()
    {
        $logs = Log::with('user')->paginate(20);
        return view('admin.logs.index', compact('logs'));
    }


}
