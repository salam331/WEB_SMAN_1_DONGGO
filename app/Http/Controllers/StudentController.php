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
        $this->middleware(['auth', 'role:admin'])->except(['dashboard', 'schedule', 'attendance', 'grades', 'materials', 'announcements', 'invoices', 'library', 'borrowBook', 'returnBook', 'gallery', 'messages', 'sendMessage', 'notifications']);
    }

    public function index(Request $request)
    {
        $query = Student::with(['user', 'classRoom']);
        
        if ($q = $request->query('q')) {
            $query->where(function($query) use ($q) {
                $query->whereHas('user', function($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%");
                })
                ->orWhere('nis', 'like', "%{$q}%")
                ->orWhere('nisn', 'like', "%{$q}%");
            });
        }

        if ($class = $request->query('class')) {
            $query->where('rombel_id', $class);
        }

        $students = $query->latest()
                         ->paginate(20)
                         ->withQueryString();

        // Get classes for filter dropdown
        $classes = ClassRoom::all();
                         
        return view('admin.students.index', compact('students', 'classes'));
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
            'total_materials' => \App\Models\Material::where('class_id', $student->class_id)
                ->orWhereNull('class_id')
                ->count(),
            'unread_messages' => \App\Models\Message::where('receiver_id', auth()->id())
                ->where('is_read', false)
                ->count(),
            'unread_notifications' => \App\Models\Notification::where('user_id', auth()->id())
                ->where('is_read', false)
                ->count(),
        ];

        return view('student.dashboard', compact('stats'));
    }

    // Schedule
    public function schedule()
    {
        $student = auth()->user()->student;
        $schedules = \App\Models\Schedule::where('class_id', $student->class_id)
            ->with('subject', 'teacher.user')
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('student.schedule', compact('schedules'));
    }

    // Attendance
    public function attendance()
    {
        $student = auth()->user()->student;
        $attendances = \App\Models\Attendance::where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('student.attendance', compact('attendances'));
    }

    // Grades
    public function grades()
    {
        $student = auth()->user()->student;
        $examResults = \App\Models\ExamResult::where('student_id', $student->id)
            ->with('exam.subject', 'exam.classRoom')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('student.grades', compact('examResults'));
    }

    // Materials
    public function materials()
    {
        $student = auth()->user()->student;
        $materials = \App\Models\Material::where('class_id', $student->class_id)
            ->orWhereNull('class_id')
            ->with('subject', 'teacher.user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('student.materials', compact('materials'));
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

        return view('student.announcements', compact('announcements'));
    }

    // Invoices/Payments
    public function invoices()
    {
        $student = auth()->user()->student;
        $invoices = \App\Models\Invoice::where('student_id', $student->id)
            ->with('createdBy')
            ->orderBy('due_date', 'desc')
            ->paginate(20);

        return view('student.invoices', compact('invoices'));
    }

    // Library
    public function library()
    {
        $books = \App\Models\LibraryBook::paginate(20);
        $borrowedBooks = \App\Models\BookBorrow::where('student_id', auth()->user()->student->id)
            ->whereNull('returned_at')
            ->with('book')
            ->get();

        return view('student.library', compact('books', 'borrowedBooks'));
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
        $galleries = Gallery::where('is_public', true)
            ->paginate(20);

        return view('student.gallery', compact('galleries'));
    }

    // Messages
    public function messages()
    {
        $messages = Message::where('receiver_id', auth()->id())
            ->orWhere('sender_id', auth()->id())
            ->with('sender', 'receiver')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('student.messages', compact('messages'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'subject' => $request->subject,
            'body' => $request->body,
            'is_read' => false,
        ]);

        return back()->with('success', 'Message sent successfully.');
    }

    // Notifications
    public function notifications()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Mark as read
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('student.notifications', compact('notifications'));
    }
}
