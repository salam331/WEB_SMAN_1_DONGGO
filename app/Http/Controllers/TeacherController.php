<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectTeacher;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Announcement;
use App\Models\Material;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin|guru')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    // --- Admin / CRUD for teachers (resourceful) ---
    public function index(Request $request)
    {
        $query = Teacher::query();
        if ($search = $request->query('q')) {
            $query->where('nama', 'like', "%{$search}%")->orWhere('nip', 'like', "%{$search}%");
        }
        $teachers = $query->orderBy('nama')->paginate(15)->withQueryString();
        return view('teachers.index', compact('teachers'));
    }

    public function create()
    {
        $this->authorize('create', Teacher::class);
        return view('teachers.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Teacher::class);
        $data = $request->validate([
            'nip' => 'required|string|unique:teachers,nip',
            'nama' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
        ]);

        Teacher::create($data);
        return redirect()->route('admin.teachers')->with('success', 'Guru berhasil ditambahkan.');
    }

    public function show(Teacher $teacher)
    {
        return view('teachers.show', compact('teacher'));
    }

    public function edit(Teacher $teacher)
    {
        $this->authorize('update', $teacher);
        return view('teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $this->authorize('update', $teacher);
        $data = $request->validate([
            'nip' => 'required|string|unique:teachers,nip,' . $teacher->id,
            'nama' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
        ]);

        $teacher->update($data);
        return redirect()->route('admin.teachers')->with('success', 'Guru berhasil diperbarui.');
    }

    public function destroy(Teacher $teacher)
    {
        $this->authorize('delete', $teacher);
        $teacher->delete();
        return redirect()->route('admin.teachers')->with('success', 'Guru berhasil dihapus.');
    }

    // --- Teacher-facing actions ---
    public function dashboard()
    {
        $user = auth()->user();
        $teacher = $user->teacher;

        if (!$teacher) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Akun Anda tidak memiliki hak akses.');
        }

        $stats = [
            'total_classes' => $teacher->subjectTeachers()->distinct('class_id')->count(),
            'total_subjects' => $teacher->subjectTeachers()->distinct('subject_id')->count(),
            'today_schedules' => Schedule::where('teacher_id', $teacher->id)
                ->where('day', Carbon::now()->format('l'))
                ->count(),
        ];

        return view('teachers.dashboard', compact('stats'));
    }

    // Class Management
    public function classes()
    {
        $teacher = auth()->user()->teacher;
        $classes = ClassRoom::whereHas('subjectTeachers', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->with('homeroomTeacher.user')->get();

        return view('teachers.classes.index', compact('classes'));
    }

    public function classDetail($classId)
    {
        $class = ClassRoom::with('students.user')->findOrFail($classId);
        $teacher = auth()->user()->teacher;

        if (!$class->subjectTeachers()->where('teacher_id', $teacher->id)->exists()) {
            abort(403);
        }

        return view('teachers.classes.detail', compact('class'));
    }

    // Schedule Management
    public function schedules()
    {
        $teacher = auth()->user()->teacher;
        $schedules = Schedule::where('teacher_id', $teacher->id)
            ->with('classRoom', 'subject')
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('teachers.schedules.index', compact('schedules'));
    }

    // Attendance Management
    public function attendances(Request $request)
    {
        $teacher = auth()->user()->teacher;

        // Ambil semua schedule (jadwal) yang diajar oleh guru ini
        $scheduleIds = \App\Models\Schedule::where('teacher_id', $teacher->id)->pluck('id');

        // Ambil absensi yang sudah diinput, dikelompokkan per mapel, kelas, dan tanggal (bukan per siswa)
        $query = \DB::table('attendances')
            ->join('schedules', 'attendances.schedule_id', '=', 'schedules.id')
            ->join('subjects', 'schedules.subject_id', '=', 'subjects.id')
            ->join('class_rooms', 'schedules.class_id', '=', 'class_rooms.id')
            ->whereIn('attendances.schedule_id', $scheduleIds)
            ->select(
                'attendances.schedule_id',
                'attendances.date',
                'subjects.name as subject_name',
                'class_rooms.name as class_name',
                \DB::raw('COUNT(DISTINCT attendances.student_id) as total_students'),
                \DB::raw('SUM(CASE WHEN attendances.status = "present" THEN 1 ELSE 0 END) as present_count'),
                \DB::raw('SUM(CASE WHEN attendances.status = "late" THEN 1 ELSE 0 END) as late_count'),
                \DB::raw('SUM(CASE WHEN attendances.status = "absent" THEN 1 ELSE 0 END) as absent_count'),
                \DB::raw('SUM(CASE WHEN attendances.status = "excused" THEN 1 ELSE 0 END) as excused_count')
            );

        // Filter tanggal jika ada
        if ($request->filled('date')) {
            $query->where('attendances.date', $request->date);
        }

        // Group by schedule, class, subject, dan tanggal
        $attendances = $query->groupBy('attendances.schedule_id', 'attendances.date', 'subjects.name', 'class_rooms.name')
            ->orderBy('attendances.date', 'desc')
            ->paginate(20)
            ->appends($request->query());

        return view('teachers.attendances.index', compact('attendances'));
    }

    public function createAttendance()
    {
        $teacher = auth()->user()->teacher;

        // Get classes taught by this teacher
        $classes = ClassRoom::whereHas('subjectTeachers', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->get();

        return view('teachers.attendances.create', compact('classes'));
    }

    public function markAttendance(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:class_rooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'students' => 'required|array',
            'students.*.student_id' => 'required|exists:students,id',
            'students.*.status' => 'required|in:present,late,absent,excused',
            'students.*.remark' => 'nullable|string',
        ]);

        $teacher = auth()->user()->teacher;

        // Verify teacher teaches this subject in this class
        $schedule = Schedule::where('class_id', $request->class_id)
            ->where('subject_id', $request->subject_id)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$schedule) {
            return back()->with('error', 'Anda tidak mengajar mata pelajaran ini di kelas tersebut.');
        }

        foreach ($request->students as $attendanceData) {
            $student = Student::findOrFail($attendanceData['student_id']);
            // Perbaikan: cek berdasarkan rombel_id (relasi ke kelas)
            if ($student->rombel_id !== (int)$request->class_id) {
                continue; // Lewati jika siswa bukan dari kelas yang dipilih
            }

            Attendance::updateOrCreate(
                [
                    'student_id' => $attendanceData['student_id'],
                    'schedule_id' => $schedule->id,
                    'date' => $request->date,
                ],
                [
                    'status' => $attendanceData['status'],
                    'remark' => $attendanceData['remark'] ?? null,
                    'recorded_by' => auth()->id(),
                ]
            );
        }

        return redirect()->route('teachers.attendances')->with('success', 'Absensi berhasil disimpan.');
    }

    public function showAttendance($scheduleId, $date)
    {
        $teacher = auth()->user()->teacher;

        // Verify teacher teaches this schedule
        $schedule = Schedule::where('id', $scheduleId)->where('teacher_id', $teacher->id)->firstOrFail();

        $attendances = Attendance::where('schedule_id', $scheduleId)
            ->where('date', $date)
            ->with('student.user', 'recordedBy')
            ->get();

        return view('teachers.attendances.show', compact('attendances', 'schedule', 'date'));
    }

    public function editAttendance($scheduleId, $date)
    {
        $teacher = auth()->user()->teacher;

        // Verify teacher teaches this schedule
        $schedule = Schedule::where('id', $scheduleId)->where('teacher_id', $teacher->id)->firstOrFail();

        $attendances = Attendance::where('schedule_id', $scheduleId)
            ->where('date', $date)
            ->with('student.user')
            ->get();

        return view('teachers.attendances.edit', compact('attendances', 'schedule', 'date'));
    }

    public function updateAttendance(Request $request, $scheduleId, $date)
    {
        $request->validate([
            'students' => 'required|array',
            'students.*.student_id' => 'required|exists:students,id',
            'students.*.status' => 'required|in:present,late,absent,excused',
            'students.*.remark' => 'nullable|string',
        ]);

        $teacher = auth()->user()->teacher;

        // Verify teacher teaches this schedule
        $schedule = Schedule::where('id', $scheduleId)->where('teacher_id', $teacher->id)->firstOrFail();

        foreach ($request->students as $attendanceData) {
            $student = Student::findOrFail($attendanceData['student_id']);
            if ($student->rombel_id !== $schedule->class_id) {
                continue; // Skip if student is not in the scheduled class
            }

            Attendance::updateOrCreate(
                [
                    'student_id' => $attendanceData['student_id'],
                    'schedule_id' => $scheduleId,
                    'date' => $date,
                ],
                [
                    'status' => $attendanceData['status'],
                    'remark' => $attendanceData['remark'] ?? null,
                    'recorded_by' => auth()->id(),
                ]
            );
        }

        return redirect()->route('teachers.attendances')->with('success', 'Absensi berhasil diperbarui.');
    }

    public function destroyAttendance($scheduleId, $date)
    {
        $teacher = auth()->user()->teacher;

        // Verify teacher teaches this schedule
        $schedule = Schedule::where('id', $scheduleId)->where('teacher_id', $teacher->id)->firstOrFail();

        Attendance::where('schedule_id', $scheduleId)->where('date', $date)->delete();

        return redirect()->route('teacher.attendances')->with('success', 'Absensi berhasil dihapus.');
    }

    public function getSubjectsForClass($classId)
    {
        $teacher = auth()->user()->teacher;

        $subjects = Subject::whereHas('subjectTeachers', function ($query) use ($teacher, $classId) {
            $query->where('teacher_id', $teacher->id)
                  ->where('class_id', $classId);
        })->get();

        return response()->json($subjects);
    }

    public function getStudentsForClass($classId)
    {
        $teacher = auth()->user()->teacher;

        // Verify teacher teaches this class
        $hasAccess = \App\Models\SubjectTeacher::where('teacher_id', $teacher->id)
            ->where('class_id', $classId)
            ->exists();

        if (!$hasAccess) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $students = Student::where('rombel_id', $classId)
            ->with('user')
            ->get();

        return response()->json($students);
    }

    // Exam Management
    public function exams()
    {
        $teacher = auth()->user()->teacher;
        $exams = Exam::whereHas('classRoom.subjectTeachers', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->with('classRoom', 'subject')->paginate(20);

        $classes = ClassRoom::whereHas('subjectTeachers', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->get();

        $subjects = Subject::whereHas('subjectTeachers', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->get();

        return view('teachers.exams.index', compact('exams', 'classes', 'subjects'));
    }

    public function createExam()
    {
        $teacher = auth()->user()->teacher;
        $classes = ClassRoom::whereHas('subjectTeachers', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->get();

        $subjects = Subject::whereHas('subjectTeachers', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->get();

        return view('teachers.exams.create', compact('classes', 'subjects'));
    }

    public function storeExam(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:class_rooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_score' => 'required|numeric|min:0',
        ]);

        $teacher = auth()->user()->teacher;

        if (
            !SubjectTeacher::where('teacher_id', $teacher->id)
                ->where('class_id', $request->class_id)
                ->where('subject_id', $request->subject_id)
                ->exists()
        ) {
            abort(403);
        }

        Exam::create($request->all());

        return redirect()->route('teacher.exams')->with('success', 'Exam created successfully.');
    }

    // Grade Management
    public function grades()
    {
        $teacher = auth()->user()->teacher;
        $examResults = ExamResult::whereHas('exam.classRoom.subjectTeachers', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->with('exam', 'student.user')->paginate(20);

        return view('teachers.grades.index', compact('examResults'));
    }

    public function inputGrades($examId)
    {
        $exam = Exam::with('classRoom.students.user', 'examResults')->findOrFail($examId);
        $teacher = auth()->user()->teacher;

        if (!$exam->classRoom->subjectTeachers()->where('teacher_id', $teacher->id)->exists()) {
            abort(403);
        }

        // Get existing exam results keyed by student_id
        $existingResults = $exam->examResults->keyBy('student_id');

        return view('teachers.grades.input', compact('exam', 'existingResults'));
    }

    public function showGrades($examId)
    {
        $exam = Exam::with('classRoom.students.user', 'subject', 'examResults.student.user')->findOrFail($examId);
        $teacher = auth()->user()->teacher;

        if (!$exam->classRoom->subjectTeachers()->where('teacher_id', $teacher->id)->exists()) {
            abort(403);
        }

        return view('teachers.grades.show', compact('exam'));
    }

    public function storeGrades(Request $request, $examId)
    {
        $exam = Exam::findOrFail($examId);
        $teacher = auth()->user()->teacher;

        if (!$exam->classRoom->subjectTeachers()->where('teacher_id', $teacher->id)->exists()) {
            abort(403);
        }

        $request->validate([
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.score' => 'required|numeric|min:0|max:' . $exam->total_score,
            'grades.*.grade' => 'required|string|max:5',
            'grades.*.remark' => 'nullable|string',
        ]);

        foreach ($request->grades as $gradeData) {
            ExamResult::updateOrCreate(
                [
                    'exam_id' => $examId,
                    'student_id' => $gradeData['student_id'],
                ],
                [
                    'score' => $gradeData['score'],
                    'grade' => $gradeData['grade'],
                    'remark' => $gradeData['remark'] ?? null,
                ]
            );
        }

        return redirect()->route('teachers.grades')->with('success', 'Grades saved successfully.');
    }

    // Material Management
    public function materials()
    {
        $teacher = auth()->user()->teacher;
        $materials = Material::where('teacher_id', $teacher->id)
            ->with('subject', 'classRoom')
            ->paginate(20);

        return view('teachers.materials.index', compact('materials'));
    }

    // Announcement Management
    public function announcements()
    {
        $announcements = Announcement::where('posted_by', auth()->id())
            ->paginate(20);

        return view('teachers.announcements.index', compact('announcements'));
    }

    public function createAnnouncement()
    {
        return view('teachers.announcements.create');
    }

    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_audience' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'is_published' => 'boolean',
        ]);

        $data = $request->all();
        $data['posted_by'] = auth()->id();

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('announcements', 'public');
        }

        Announcement::create($data);

        return redirect()->route('teachers.announcements')->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function editAnnouncement(Announcement $announcement)
    {
        // Ensure teacher can only edit their own announcements
        if ($announcement->posted_by !== auth()->id()) {
            abort(403);
        }

        return view('teachers.announcements.edit', compact('announcement'));
    }

    public function updateAnnouncement(Request $request, Announcement $announcement)
    {
        // Ensure teacher can only update their own announcements
        if ($announcement->posted_by !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_audience' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'is_published' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_published'] = $request->has('is_published');

        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($announcement->attachment) {
                \Storage::disk('public')->delete($announcement->attachment);
            }
            $data['attachment'] = $request->file('attachment')->store('announcements', 'public');
        }

        $announcement->update($data);

        return redirect()->route('teachers.announcements')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroyAnnouncement(Announcement $announcement)
    {
        // Ensure teacher can only delete their own announcements
        if ($announcement->posted_by !== auth()->id()) {
            abort(403);
        }

        // Delete attachment if exists
        if ($announcement->attachment) {
            \Storage::disk('public')->delete($announcement->attachment);
        }

        $announcement->delete();

        return redirect()->route('teachers.announcements')->with('success', 'Pengumuman berhasil dihapus.');
    }

    public function publishAnnouncement(Announcement $announcement)
    {
        // Ensure teacher can only publish their own announcements
        if ($announcement->posted_by !== auth()->id()) {
            abort(403);
        }

        $announcement->update(['is_published' => true]);

        return redirect()->route('teachers.announcements')->with('success', 'Pengumuman berhasil dipublish.');
    }

    // Messages
    public function messages()
    {
        $receivedMessages = Message::where('receiver_id', auth()->id())
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'inbox_page');

        // Get all sent messages and group them by subject and recipient_type/receiver_id
        $allSentMessages = Message::where('sender_id', auth()->id())
            ->with('receiver')
            ->orderBy('created_at', 'desc')
            ->get();

        $groupedMessages = collect();
        $grouped = $allSentMessages->groupBy(function ($message) {
            if ($message->recipient_type) {
                return $message->subject . '|' . $message->recipient_type;
            } else {
                return $message->subject . '|' . $message->receiver_id;
            }
        });

        foreach ($grouped as $key => $group) {
            $firstMessage = $group->first();
            $firstMessage->message_count = $group->count();
            $firstMessage->latest_created_at = $group->max('created_at');
            $groupedMessages->push($firstMessage);
        }

        $groupedMessages = $groupedMessages->sortByDesc('latest_created_at')->values();
        $perPage = 10;
        $currentPage = request()->get('sent_page', 1);
        $paginatedUniqueSentMessages = new \Illuminate\Pagination\LengthAwarePaginator(
            $groupedMessages->forPage($currentPage, $perPage),
            $groupedMessages->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'pageName' => 'sent_page']
        );

        $uniqueSentMessages = $paginatedUniqueSentMessages;

        return view('teachers.messages.index', compact('receivedMessages', 'uniqueSentMessages'));
    }

    public function storeMessage(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|string',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $recipientIds = [];

        // Handle special "all_" prefixed recipient IDs
        if (str_starts_with($request->recipient_id, 'all_')) {
            $group = str_replace('all_', '', $request->recipient_id);

            switch ($group) {
                case 'students':
                    $recipientIds = \App\Models\Student::pluck('user_id')->toArray();
                    break;
                case 'parents':
                    $recipientIds = \App\Models\Student::whereNotNull('parent_id')
                        ->with('parent.user')
                        ->get()
                        ->pluck('parent.user.id')
                        ->filter()
                        ->unique()
                        ->toArray();
                    break;
                case 'teachers':
                    $recipientIds = \App\Models\User::role('guru')->pluck('id')->toArray();
                    break;
                case 'admins':
                    $recipientIds = \App\Models\User::role('admin')->pluck('id')->toArray();
                    break;
                case 'users':
                    $recipientIds = \App\Models\User::pluck('id')->toArray();
                    break;
            }
        } else {
            // Single recipient
            $request->validate([
                'recipient_id' => 'exists:users,id',
            ]);
            $recipientIds = [$request->recipient_id];
        }

        // Remove sender from recipients if present
        $recipientIds = array_diff($recipientIds, [auth()->id()]);

        // Determine recipient type
        $recipientType = null;
        if (str_starts_with($request->recipient_id, 'all_')) {
            $recipientType = str_replace('all_', '', $request->recipient_id);
        }

        // Create messages for each recipient
        foreach ($recipientIds as $recipientId) {
            Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $recipientId,
                'recipient_type' => $recipientType,
                'subject' => $request->subject,
                'body' => $request->content,
                'is_read' => false,
            ]);
        }

        return redirect()->back()->with('success', 'Pesan berhasil dikirim.');
    }

    public function editMessage(Message $message)
    {
        // Only allow editing if user is the sender and message hasn't been read by receiver
        if ($message->sender_id !== auth()->id()) {
            abort(403);
        }

        if ($message->is_read) {
            return redirect()->back()->with('error', 'Pesan tidak dapat diedit karena sudah dibaca oleh penerima.');
        }

        return view('teachers.messages.edit', compact('message'));
    }

    public function updateMessage(Request $request, Message $message)
    {
        // Only allow updating if user is the sender and message hasn't been read by receiver
        if ($message->sender_id !== auth()->id()) {
            abort(403);
        }

        if ($message->is_read) {
            return redirect()->back()->with('error', 'Pesan tidak dapat diedit karena sudah dibaca oleh penerima.');
        }

        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $message->update([
            'subject' => $request->subject,
            'body' => $request->content,
        ]);

        return redirect()->route('teachers.messages')->with('success', 'Pesan berhasil diperbarui.');
    }

    public function destroyMessage(Message $message)
    {
        // Only allow deleting if user is the sender
        if ($message->sender_id !== auth()->id()) {
            abort(403);
        }

        $message->delete();

        return redirect()->back()->with('success', 'Pesan berhasil dihapus.');
    }

    public function showMessage(Message $message)
    {
        // Check if user is sender or receiver
        if ($message->sender_id !== auth()->id() && $message->receiver_id !== auth()->id()) {
            abort(403);
        }

        // Mark as read if receiver
        if ($message->receiver_id === auth()->id() && !$message->is_read) {
            $message->update(['is_read' => true]);
        }

        return response()->json([
            'id' => $message->id,
            'subject' => $message->subject,
            'content' => $message->body,
            'sender' => $message->sender,
            'receiver' => $message->receiver,
            'created_at' => $message->created_at,
        ]);
    }

    // Notifications
    public function notifications()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('teachers.notifications.index', compact('notifications'));
    }
}
