<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\ClassRoom;
use App\Models\ExamResult;
use App\Models\GradeItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:guru');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $teacher = auth()->user()->teacher;

        $query = Exam::with(['subject', 'classRoom'])
            ->whereHas('subject', function($q) use ($teacher) {
                $q->whereHas('subjectTeachers', function($sq) use ($teacher) {
                    $sq->where('teacher_id', $teacher->id);
                });
            })
            ->whereHas('classRoom', function($q) use ($teacher) {
                $q->whereHas('subjectTeachers', function($sq) use ($teacher) {
                    $sq->where('teacher_id', $teacher->id);
                });
            });

        // Filter by subject
        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter by class
        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        // Search functionality
        if ($request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $exams = $query->orderBy('start_date', 'desc')
                       ->paginate(20)
                       ->withQueryString();

        $subjects = Subject::whereHas('subjectTeachers', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->orderBy('name')->get();

        $classes = ClassRoom::whereHas('subjectTeachers', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->orderBy('name')->get();

        return view('teachers.exams.index', compact('exams', 'subjects', 'classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teacher = auth()->user()->teacher;

        $subjects = Subject::whereHas('subjectTeachers', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->orderBy('name')->get();

        $classes = ClassRoom::whereHas('subjectTeachers', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->orderBy('name')->get();

        return view('teachers.exams.create', compact('subjects', 'classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $teacher = auth()->user()->teacher;

        $data = $request->validate([
            'class_id' => ['required', 'exists:class_rooms,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'total_score' => ['required', 'integer', 'min:1', 'max:100'],
            'grade_items' => ['nullable', 'array'],
            'grade_items.*.type' => ['required_with:grade_items', 'string', 'max:50'],
            'grade_items.*.max_score' => ['required_with:grade_items', 'integer', 'min:1'],
        ]);

        // Tambahkan teacher_id ke data
        $data['teacher_id'] = $teacher->id;

        // Check if teacher is assigned to this subject/class combination
        $isAssigned = $teacher->subjectTeachers()
            ->where('subject_id', $data['subject_id'])
            ->where('class_id', $data['class_id'])
            ->exists();

        if (!$isAssigned) {
            return back()
                ->withInput()
                ->with('error', 'Anda tidak memiliki akses untuk membuat ujian pada mata pelajaran dan kelas ini.');
        }

        try {
            $exam = Exam::create($data);

            // Create grade items if provided
            if ($request->grade_items) {
                foreach ($request->grade_items as $item) {
                    $exam->gradeItems()->create([
                        'type' => $item['type'],
                        'max_score' => $item['max_score'],
                    ]);
                }
            }

            return redirect()
                ->route('teacher.exams')
                ->with('success', 'Ujian berhasil dibuat.');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat ujian. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Exam $exam)
    {
        // Check access permission
        if (!$this->canAccessExam($exam)) {
            abort(403);
        }

        $exam->load(['subject', 'classRoom', 'gradeItems', 'examResults.student.user']);

        return view('teachers.exams.show', compact('exam'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exam $exam)
    {
        // Check access permission
        if (!$this->canAccessExam($exam)) {
            abort(403);
        }

        // Don't allow editing if exam has results
        if ($exam->examResults()->exists()) {
            return back()->with('error', 'Tidak dapat mengedit ujian yang sudah memiliki hasil.');
        }

        $teacher = auth()->user()->teacher;

        $subjects = Subject::whereHas('subjectTeachers', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->orderBy('name')->get();

        $classes = ClassRoom::whereHas('subjectTeachers', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->orderBy('name')->get();

        return view('teachers.exams.edit', compact('exam', 'subjects', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Exam $exam)
    {
        // Check access permission
        if (!$this->canAccessExam($exam)) {
            abort(403);
        }

        // Don't allow editing if exam has results
        if ($exam->examResults()->exists()) {
            return back()->with('error', 'Tidak dapat mengedit ujian yang sudah memiliki hasil.');
        }

        $data = $request->validate([
            'class_id' => ['required', 'exists:class_rooms,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'total_score' => ['required', 'integer', 'min:1', 'max:100'],
            'grade_items' => ['nullable', 'array'],
            'grade_items.*.type' => ['required_with:grade_items', 'string', 'max:50'],
            'grade_items.*.max_score' => ['required_with:grade_items', 'integer', 'min:1'],
        ]);

        try {
            $exam->update($data);

            // Update grade items
            $exam->gradeItems()->delete(); // Delete existing
            if ($request->grade_items) {
                foreach ($request->grade_items as $item) {
                    $exam->gradeItems()->create([
                        'type' => $item['type'],
                        'max_score' => $item['max_score'],
                    ]);
                }
            }

            return redirect()
                ->route('teacher.exams')
                ->with('success', 'Ujian berhasil diperbarui.');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui ujian. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exam $exam)
    {
        // Check access permission
        if (!$this->canAccessExam($exam)) {
            abort(403);
        }

        // Don't allow deletion if exam has results
        if ($exam->examResults()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus ujian yang sudah memiliki hasil.');
        }

        try {
            $exam->gradeItems()->delete(); // Delete related grade items
            $exam->delete();

            return redirect()
                ->route('teacher.exams')
                ->with('success', 'Ujian berhasil dihapus.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus ujian. Silakan coba lagi.');
        }
    }

    /**
     * Show form to input grades for an exam
     */
    public function inputGrades(Exam $exam)
    {
        // Check access permission
        if (!$this->canAccessExam($exam)) {
            abort(403);
        }

        $exam->load(['subject', 'classRoom', 'gradeItems', 'examResults.student.user']);

        // Get students in the class
        $students = $exam->classRoom->students()->with('user')->get();

        return view('teachers.exams.input_grades', compact('exam', 'students'));
    }

    /**
     * Store grades for an exam
     */
    public function storeGrades(Request $request, Exam $exam)
    {
        // Check access permission
        if (!$this->canAccessExam($exam)) {
            abort(403);
        }

        $request->validate([
            'grades' => ['required', 'array'],
            'grades.*.student_id' => ['required', 'exists:students,id'],
            'grades.*.score' => ['required', 'numeric', 'min:0', 'max:' . $exam->total_score],
            'grades.*.grade' => ['nullable', 'string', 'max:5'],
            'grades.*.remark' => ['nullable', 'string'],
        ]);

        try {
            foreach ($request->grades as $gradeData) {
                ExamResult::updateOrCreate(
                    [
                        'exam_id' => $exam->id,
                        'student_id' => $gradeData['student_id'],
                    ],
                    [
                        'score' => $gradeData['score'],
                        'grade' => $gradeData['grade'] ?? $this->calculateGrade($gradeData['score'], $exam->subject->kkm),
                        'remark' => $gradeData['remark'] ?? null,
                    ]
                );
            }

            return redirect()
                ->route('teacher.exams.show', $exam)
                ->with('success', 'Nilai berhasil disimpan.');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan nilai. Silakan coba lagi.');
        }
    }

    /**
     * Check if teacher can access the exam
     */
    private function canAccessExam(Exam $exam)
    {
        $teacher = auth()->user()->teacher;
        return $teacher->subjectTeachers()
            ->where('subject_id', $exam->subject_id)
            ->where('class_id', $exam->class_id)
            ->exists();
    }

    /**
     * Calculate grade based on score and KKM
     */
    private function calculateGrade($score, $kkm)
    {
        if ($score >= 85) return 'A';
        if ($score >= 75) return 'B';
        if ($score >= 65) return 'C';
        if ($score >= $kkm) return 'D';
        return 'E';
    }
}
