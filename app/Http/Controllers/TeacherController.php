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

        return view('teacher.classes.index', compact('classes'));
    }

    public function classDetail($classId)
    {
        $class = ClassRoom::with('students.user')->findOrFail($classId);
        $teacher = auth()->user()->teacher;

        if (!$class->subjectTeachers()->where('teacher_id', $teacher->id)->exists()) {
            abort(403);
        }

        return view('teacher.classes.detail', compact('class'));
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

        return view('teacher.schedules.index', compact('schedules'));
    }

    // Attendance Management
    public function attendances()
    {
        $teacher = auth()->user()->teacher;
        $attendances = Attendance::whereHas('student.classRoom.subjectTeachers', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->with('student.user', 'recordedBy')->paginate(20);

        return view('teacher.attendances.index', compact('attendances'));
    }

    public function markAttendance(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'status' => 'required|in:present,late,absent,permit',
            'remark' => 'nullable|string',
        ]);

        $teacher = auth()->user()->teacher;

        $student = Student::findOrFail($request->student_id);
        if (!$student->classRoom->subjectTeachers()->where('teacher_id', $teacher->id)->exists()) {
            abort(403);
        }

        Attendance::updateOrCreate(
            [
                'student_id' => $request->student_id,
                'date' => $request->date,
            ],
            [
                'status' => $request->status,
                'remark' => $request->remark,
                'recorded_by' => auth()->id(),
            ]
        );

        return back()->with('success', 'Attendance marked successfully.');
    }

    // Exam Management
    public function exams()
    {
        $teacher = auth()->user()->teacher;
        $exams = Exam::whereHas('classRoom.subjectTeachers', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->with('classRoom', 'subject')->paginate(20);

        return view('teacher.exams.index', compact('exams'));
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

        return view('teacher.exams.create', compact('classes', 'subjects'));
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

        return view('teacher.grades.index', compact('examResults'));
    }

    public function inputGrades($examId)
    {
        $exam = Exam::with('classRoom.students.user')->findOrFail($examId);
        $teacher = auth()->user()->teacher;

        if (!$exam->classRoom->subjectTeachers()->where('teacher_id', $teacher->id)->exists()) {
            abort(403);
        }

        return view('teacher.grades.input', compact('exam'));
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

        return redirect()->route('teacher.grades')->with('success', 'Grades saved successfully.');
    }

    // Material Management
    public function materials()
    {
        $teacher = auth()->user()->teacher;
        $materials = Material::where('teacher_id', $teacher->id)
            ->with('subject', 'classRoom')
            ->paginate(20);

        return view('teacher.materials.index', compact('materials'));
    }

    // Announcement Management
    public function announcements()
    {
        $announcements = Announcement::where('posted_by', auth()->id())
            ->paginate(20);

        return view('teacher.announcements.index', compact('announcements'));
    }

    // Messages
    public function messages()
    {
        $messages = Message::where('receiver_id', auth()->id())
            ->orWhere('sender_id', auth()->id())
            ->with('sender', 'receiver')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('teacher.messages.index', compact('messages'));
    }

    // Notifications
    public function notifications()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('teacher.notifications.index', compact('notifications'));
    }
}
