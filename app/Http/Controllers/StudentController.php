<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ParentModel;
use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentController extends BaseController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['auth', 'role:admin'])->except(['dashboard', 'schedule', 'attendance', 'showAttendance', 'grades', 'materials', 'showMaterial', 'downloadMaterial', 'announcements', 'invoices', 'library', 'borrowBook', 'returnBook', 'gallery', 'messages', 'sendMessage', 'notifications']);
    }

    public function index(Request $request)
    {
        $query = Student::with(['user', 'classRoom']);

        if ($search = $request->query('search')) {
            $query->where(function($query) use ($search) {
                $query->whereHas('user', function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('nis', 'like', "%{$search}%")
                ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        if ($classId = $request->query('class_id')) {
            $query->where('rombel_id', $classId);
        }

        $students = $query->latest()
                         ->paginate(20)
                         ->withQueryString();

        // Get classes for filter dropdown
        $classes = ClassRoom::all();

        return view('admin.students.index', compact('students', 'classes'));
    }

    public function show(Student $student)
    {
        $student->load('user', 'classRoom', 'parent.user');
        return view('admin.students.show', compact('student'));
    }

    public function create()
    {
        $parents = ParentModel::all();
        $classes = ClassRoom::all();
        return view('admin.students.create', compact('parents','classes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'nis' => 'required|string|unique:students,nis',
            'nisn' => 'nullable|string|unique:students,nisn',
            'class_id' => 'nullable|exists:class_rooms,id',
            'parent_id' => 'nullable|exists:parents,id',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
            'address' => 'nullable|string|max:255',
        ]);

        \DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'is_active' => true,
            ]);

            // assign role siswa if exists
            if (\Spatie\Permission\Models\Role::where('name','siswa')->exists()) {
                $user->assignRole('siswa');
            }

            Student::create([
                'user_id' => $user->id,
                'nis' => $data['nis'],
                'nisn' => $data['nisn'],
                'birth_place' => $data['birth_place'],
                'birth_date' => $data['birth_date'],
                'gender' => $data['gender'],
                'address' => $data['address'],
                'rombel_id' => $data['class_id'],
                'parent_id' => $data['parent_id'] ?? null,
            ]);

            \DB::commit();
            return redirect()
                ->route('admin.students')
                ->with('success', 'Siswa berhasil ditambahkan.');
                
        } catch (\Exception $e) {
            \DB::rollback();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menambahkan siswa. ' . $e->getMessage()]);
        }
    }

    public function edit(Student $student)
    {
        $parents = ParentModel::all();
        $classes = ClassRoom::all();
        $student->load('user');
        return view('admin.students.edit', compact('student','parents','classes'));
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$student->user_id,
            'password' => 'nullable|string|min:8|confirmed',
            'nis' => 'required|string|unique:students,nis,'.$student->id,
            'nisn' => 'nullable|string|unique:students,nisn,'.$student->id,
            'class_id' => 'nullable|exists:class_rooms,id',
            'parent_id' => 'nullable|exists:parents,id',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
            'address' => 'nullable|string|max:255',
        ]);

        \DB::beginTransaction();
        try {
            $student->user->update([
                'name' => $data['name'],
                'email' => $data['email']
            ]);

            if (!empty($data['password'])) {
                $student->user->update([
                    'password' => Hash::make($data['password'])
                ]);
            }

            $student->update([
                'nis' => $data['nis'],
                'nisn' => $data['nisn'],
                'birth_place' => $data['birth_place'],
                'birth_date' => $data['birth_date'],
                'gender' => $data['gender'],
                'address' => $data['address'],
                'rombel_id' => $data['class_id'],
                'parent_id' => $data['parent_id'],
            ]);

            \DB::commit();
            return redirect()
                ->route('admin.students')
                ->with('success', 'Data siswa berhasil diperbarui.');

        } catch (\Exception $e) {
            \DB::rollback();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal memperbarui data siswa. ' . $e->getMessage()]);
        }
    }

    public function destroy(Student $student)
    {
        \DB::beginTransaction();
        try {
            // Delete student record first
            $student->delete();
            
            // Then delete associated user if exists
            if ($student->user) {
                $student->user->delete();
            }
            
            \DB::commit();
            return redirect()
                ->route('admin.students')
                ->with('success', 'Siswa berhasil dihapus.');
                
        } catch (\Exception $e) {
            \DB::rollback();
            return back()->withErrors(['error' => 'Gagal menghapus siswa. ' . $e->getMessage()]);
        }
    }

    public function dashboard()
    {
        $student = auth()->user()->student;
        $stats = [
            'today_attendance' => \App\Models\Attendance::where('student_id', $student->id)
                ->where('date', Carbon::today())
                ->first(),
            'total_materials' => \App\Models\Material::where('class_id', $student->rombel_id)
                ->orWhereNull('class_id')
                ->count(),
            'unread_messages' => \App\Models\Message::where('receiver_id', auth()->id())
                ->where('is_read', false)
                ->count(),
            'unread_notifications' => \App\Models\Notification::where('user_id', auth()->id())
                ->where('is_read', false)
                ->count(),
        ];

        $schedules = \App\Models\Schedule::where('class_id', $student->rombel_id)
            ->with('subject', 'teacher.user', 'classRoom')
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('student.dashboard', compact('stats', 'schedules'));
    }

    // Schedule
    public function schedule()
    {
        $student = auth()->user()->student;
        $schedules = \App\Models\Schedule::where('class_id', $student->rombel_id)
            ->with('subject', 'teacher.user')
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('student.academic.schedule', compact('schedules'));
    }

    // Attendance
    public function attendance()
    {
        $student = auth()->user()->student;
        $attendances = \App\Models\Attendance::where('student_id', $student->id)
            ->with(['schedule.subject', 'schedule.teacher.user'])
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('student.academic.attendance', compact('attendances'));
    }

    // Show Attendance Detail for a specific schedule
    public function showAttendance($scheduleId)
    {
        $student = auth()->user()->student;
        $schedule = \App\Models\Schedule::where('id', $scheduleId)
            ->where('class_id', $student->rombel_id)
            ->with('subject', 'teacher.user')
            ->firstOrFail();

        $attendances = \App\Models\Attendance::where('student_id', $student->id)
            ->where('schedule_id', $scheduleId)
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('student.academic.attendance_detail', compact('schedule', 'attendances'));
    }

    // Grades
    public function grades()
    {
        $student = auth()->user()->student;
        $examResults = \App\Models\ExamResult::where('student_id', $student->id)
            ->with('exam.subject', 'exam.classRoom')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('student.academic.grades', compact('examResults'));
    }

    // Materials
    public function materials()
    {
        $student = auth()->user()->student;
        $subjects = \App\Models\Subject::whereHas('materials', function($query) use ($student) {
            $query->where('class_id', $student->rombel_id)
                  ->orWhereNull('class_id');
        })
        ->with(['materials' => function($query) use ($student) {
            $query->where('class_id', $student->rombel_id)
                  ->orWhereNull('class_id')
                  ->where('is_published', true);
        }, 'teacher.user', 'subjectTeachers.teacher.user'])
        ->orderBy('name')
        ->get();

        return view('student.resources.materials', compact('subjects'));
    }

    public function showMaterial(\App\Models\Subject $subject)
    {
        $student = auth()->user()->student;

        // Check if student has access to materials for this subject
        $hasAccess = $subject->materials()
            ->where(function($query) use ($student) {
                $query->where('class_id', $student->rombel_id)
                      ->orWhereNull('class_id');
            })
            ->where('is_published', true)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Unauthorized access to subject materials.');
        }

        $materials = $subject->materials()
            ->where('class_id', $student->rombel_id)
            ->orWhereNull('class_id')
            ->where('is_published', true)
            ->with('teacher.user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('student.resources.materials.show', compact('subject', 'materials'));
    }

    public function downloadMaterial(\App\Models\Material $material)
    {
        // Check if student has access to this material (same class or null class_id)
        $student = auth()->user()->student;
        if ($material->class_id !== $student->rombel_id && $material->class_id !== null) {
            \Log::warning("Unauthorized download attempt: Student {$student->id} tried to download material {$material->id}");
            abort(403, 'Unauthorized access to material.');
        }

        if (!$material->file_path || !\Storage::disk('public')->exists($material->file_path)) {
            \Log::error("File not found for download: Material {$material->id}, Path: {$material->file_path}, Student: {$student->id}");
            return back()->with('error', 'File tidak ditemukan.');
        }

        // Log successful download
        \Log::info("Material downloaded: Student {$student->id} ({$student->user->name}) downloaded '{$material->title}' (ID: {$material->id})");

        return \Storage::disk('public')->download($material->file_path, $material->title . '.' . pathinfo($material->file_path, PATHINFO_EXTENSION));
    }

    // Announcements
    public function announcements()
    {
        $announcements = \App\Models\Announcement::where('is_published', true)
            ->where(function($q) {
                $q->where('target_audience', 'all')
                  ->orWhere('target_audience', 'students');
            })
            ->with('postedBy')
            ->orderBy('pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('student.resources.announcements', compact('announcements'));
    }

    // Invoices/Payments
    public function invoices()
    {
        $student = auth()->user()->student;
        $invoices = \App\Models\Invoice::where('student_id', $student->id)
            ->with('createdBy')
            ->orderBy('due_date', 'desc')
            ->paginate(20);

        return view('student.finance.invoices', compact('invoices'));
    }

    // Library
    public function library()
    {
        $books = \App\Models\LibraryBook::paginate(20);
        $borrowedBooks = \App\Models\BookBorrow::where('student_id', auth()->user()->student->id)
            ->whereNull('returned_at')
            ->with('book')
            ->get();

        return view('student.resources.library', compact('books', 'borrowedBooks'));
    }

    public function borrowBook(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:library_books,id',
        ]);

        $book = \App\Models\LibraryBook::findOrFail($request->book_id);
        $student = auth()->user()->student;

        // Check if book is available
        if ($book->stock <= 0) {
            return back()->with('error', 'Book is not available.');
        }

        // Check if student already borrowed this book
        if (\App\Models\BookBorrow::where('student_id', $student->id)
            ->where('book_id', $request->book_id)
            ->whereNull('returned_at')
            ->exists()) {
            return back()->with('error', 'You have already borrowed this book.');
        }

        \App\Models\BookBorrow::create([
            'book_id' => $request->book_id,
            'student_id' => $student->id,
            'borrowed_at' => now(),
            'due_at' => now()->addDays(14), // 2 weeks
            'status' => 'borrowed',
        ]);

        $book->decrement('stock');

        return back()->with('success', 'Book borrowed successfully.');
    }

    public function returnBook($borrowId)
    {
        $borrow = \App\Models\BookBorrow::where('student_id', auth()->user()->student->id)
            ->findOrFail($borrowId);

        $borrow->update([
            'returned_at' => now(),
            'status' => 'returned',
        ]);

        $borrow->book->increment('stock');

        return back()->with('success', 'Book returned successfully.');
    }

    // Gallery
    public function gallery()
    {
        $galleries = \App\Models\Gallery::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('student.resources.gallery', compact('galleries'));
    }

    // Messages
    public function messages()
    {
        $messages = \App\Models\Message::where('receiver_id', auth()->id())
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('student.communication.messages', compact('messages'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'recipient_type' => 'required|in:admin,teacher,parent',
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        \App\Models\Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->recipient_id,
            'sender_type' => 'student',
            'recipient_type' => $request->recipient_type,
            'subject' => $request->subject,
            'body' => $request->content,
            'is_read' => false,
        ]);

        return back()->with('success', 'Pesan berhasil dikirim.');
    }

    public function getMessageRecipients($type)
    {
        $recipients = [];
        switch ($type) {
            case 'admin':
                $recipients = \App\Models\User::whereHas('roles', function($q) {
                    $q->where('name', 'admin');
                })->select('id', 'name')->get();
                break;
            case 'teacher':
                $recipients = \App\Models\User::whereHas('roles', function($q) {
                    $q->where('name', 'guru');
                })->select('id', 'name')->get();
                break;
            case 'parent':
                $recipients = \App\Models\User::whereHas('roles', function($q) {
                    $q->where('name', 'orang_tua');
                })->select('id', 'name')->get();
                break;
        }

        return response()->json($recipients);
    }

    public function viewMessage($id)
    {
        $message = \App\Models\Message::where('id', $id)
            ->where('receiver_id', auth()->id())
            ->with('sender')
            ->firstOrFail();

        // Tandai pesan sebagai sudah dibaca jika belum
        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }

        $senderName = 'Unknown';
        if ($message->sender_type == 'admin') {
            $senderName = 'Admin';
        } elseif ($message->sender) {
            $senderName = $message->sender->name ?? 'Unknown';
        }

        // Ambil isi pesan dengan fallback aman
        $body = isset($message->body) && $message->body !== null ? $message->body : (isset($message->content) ? $message->content : '-');

        return response()->json([
            'subject' => $message->subject ?? '-',
            'body' => $body,
            'sender_name' => $senderName,
            'created_at' => $message->created_at ? $message->created_at->format('d/m/Y H:i') : '-',
            'is_read' => true,
        ]);
    }

    public function deleteMessage($id)
    {
        $message = \App\Models\Message::where('id', $id)
            ->where(function($q) {
                $q->where('sender_id', auth()->id())
                  ->orWhere('receiver_id', auth()->id());
            })
            ->firstOrFail();

        $message->delete();

        return response()->json(['success' => true]);
    }

    public function markMessageAsRead($id)
    {
        $message = \App\Models\Message::where('id', $id)
            ->where('receiver_id', auth()->id())
            ->firstOrFail();

        $message->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    // Notifications
    public function notifications()
    {
        $notifications = \App\Models\Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return view('student.communication.notifications', compact('notifications', 'unreadCount'));
    }

    public function markNotificationAsRead($id)
    {
        $notification = \App\Models\Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function markAllNotificationsAsRead()
    {
        \App\Models\Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function deleteNotification($id)
    {
        $notification = \App\Models\Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $notification->delete();

        return response()->json(['success' => true]);
    }
}
