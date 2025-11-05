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

        // Update status to 'completed' for exams that have passed their exam_date
        Exam::where('teacher_id', $teacher->id)
            ->where('exam_date', '<', Carbon::today())
            ->where('status', '!=', 'completed')
            ->update(['status' => 'completed']);

        $query = Exam::with(['subject', 'classRoom'])
            ->where('teacher_id', $teacher->id);

        // Filter by subject
        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter by class
        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by exam_date range if needed
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('exam_date', [$request->start_date, $request->end_date]);
        } elseif ($request->start_date) {
            $query->where('exam_date', '>=', $request->start_date);
        } elseif ($request->end_date) {
            $query->where('exam_date', '<=', $request->end_date);
        }

        // Search functionality
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $exams = $query->orderBy('exam_date', 'desc')
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

        $subjectTeachers = $teacher->subjectTeachers()->with('subject', 'classRoom')->get();

        return view('teachers.exams.create', compact('subjects', 'classes', 'subjectTeachers'));
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
            'description' => ['nullable', 'string'],
            'exam_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required'],
            'duration' => ['required', 'integer', 'min:1'],
            'total_questions' => ['nullable', 'integer', 'min:1'],
            'total_score' => ['required', 'integer', 'min:1'],
            'passing_grade' => ['nullable', 'integer', 'min:0', 'max:100'],
            'instructions' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published'],
            'grade_items' => ['nullable', 'array'],
            'grade_items.*.type' => ['required_with:grade_items', 'string', 'max:50'],
            'grade_items.*.max_score' => ['required_with:grade_items', 'integer', 'min:1'],
        ]);

        // Tambahkan start_date dan end_date agar tidak error (pakai exam_date sebagai default)
        $data['start_date'] = $request->input('start_date', $data['exam_date']);
        $data['end_date'] = $request->input('end_date', $data['exam_date']);

        // Tambahkan teacher_id ke data
        $data['teacher_id'] = $teacher->id;

        // Check if teacher is assigned to this subject/class combination
        $isAssigned = $teacher->subjectTeachers()
            ->where('subject_id', $data['subject_id'])
            ->where('class_id', $data['class_id'])
            ->exists();

        if (!$isAssigned) {
            \Log::warning('Teacher assignment check failed', [
                'teacher_id' => $teacher->id,
                'subject_id' => $data['subject_id'],
                'class_id' => $data['class_id'],
                'exam_name' => $data['name']
            ]);

            return back()
                ->withInput()
                ->with('error', 'Anda tidak memiliki akses untuk membuat ujian pada mata pelajaran dan kelas ini. Pastikan Anda ditugaskan untuk mengajar mata pelajaran ini di kelas tersebut.');
        }

        try {
            \DB::beginTransaction();
            $exam = Exam::create($data);

            // Create grade items if provided and valid
            $gradeItems = $request->input('grade_items');
            if (is_array($gradeItems) && count($gradeItems) > 0) {
                foreach ($gradeItems as $item) {
                    if (is_array($item) && isset($item['type']) && isset($item['max_score'])) {
                        $exam->gradeItems()->create([
                            'type' => $item['type'],
                            'max_score' => $item['max_score'],
                        ]);
                    }
                }
            }

            \Log::info('Exam created successfully', [
                'exam_id' => $exam->id,
                'teacher_id' => $teacher->id,
                'subject_id' => $data['subject_id'],
                'class_id' => $data['class_id']
            ]);

            \DB::commit();
            return redirect()
                ->route('teachers.exams.index')
                ->with('success', 'Ujian berhasil dibuat.');

        } catch (\Illuminate\Database\QueryException $e) {
            \DB::rollBack();
            \Log::error('Database error during exam creation', [
                'error' => $e->getMessage(),
                'teacher_id' => $teacher->id,
                'data' => $data
            ]);

            // Handle specific database errors
            if ($e->getCode() == 23000) { // Integrity constraint violation
                return back()
                    ->withInput()
                    ->with('error', 'Data yang dimasukkan tidak valid. Periksa kembali mata pelajaran dan kelas yang dipilih.');
            }

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan database. Silakan coba lagi atau hubungi administrator.');

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Unexpected error during exam creation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'teacher_id' => $teacher->id,
                'data' => $data
            ]);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan tidak terduga. Silakan coba lagi atau hubungi administrator jika masalah berlanjut.');
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

        $examResults = $exam->examResults;

        return view('teachers.exams.show', compact('exam', 'examResults'));
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
            'description' => ['nullable', 'string'],
            'exam_date' => ['required', 'date'],
            'start_time' => ['required'],
            'duration' => ['required', 'integer', 'min:1'],
            'total_questions' => ['nullable', 'integer', 'min:1'],
            'total_score' => ['required', 'integer', 'min:1'],
            'passing_grade' => ['nullable', 'integer', 'min:0', 'max:100'],
            'instructions' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published'],
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
                ->route('teachers.exams.index')
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
                ->route('teachers.exams.index')
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

        // Get existing exam results keyed by student_id
        $existingResults = $exam->examResults->keyBy('student_id');

        return view('teachers.grades.input', compact('exam', 'students', 'existingResults'));
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
                        'submitted_at' => now(),
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
     * Export exam results to PDF
     */
    public function exportPdf(Exam $exam)
    {
        // Check access permission
        if (!$this->canAccessExam($exam)) {
            abort(403);
        }

        $exam->load(['subject', 'classRoom', 'examResults.student.user']);

        $examResults = $exam->examResults;

        // Calculate statistics
        $totalStudents = $examResults->count();
        $passedStudents = $examResults->where('score', '>=', $exam->passing_grade ?? 0)->count();
        $failedStudents = $totalStudents - $passedStudents;
        $averageScore = $totalStudents > 0 ? $examResults->avg('score') : 0;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('teachers.exams.export_pdf', compact(
            'exam',
            'examResults',
            'totalStudents',
            'passedStudents',
            'failedStudents',
            'averageScore'
        ));

        $filename = 'hasil_ujian_' . $exam->name . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';

        return $pdf->download($filename);
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
