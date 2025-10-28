<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\SubjectTeacher;
use Illuminate\Http\Request;

class SubjectController extends Controller
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

        $query = SubjectTeacher::with(['subject', 'classRoom'])
            ->where('teacher_id', $teacher->id);

        // Search functionality
        if ($request->search) {
            $search = $request->search;
            $query->whereHas('subject', function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $subjectTeachers = $query->orderBy('subject.code')
                                 ->paginate(20)
                                 ->withQueryString();

        return view('teachers.subjects.index', compact('subjectTeachers'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        $teacher = auth()->user()->teacher;

        // Check if teacher is assigned to this subject
        $subjectTeacher = SubjectTeacher::where('teacher_id', $teacher->id)
            ->where('subject_id', $subject->id)
            ->with(['subject', 'classRoom', 'materials', 'exams'])
            ->first();

        if (!$subjectTeacher) {
            abort(403);
        }

        return view('teachers.subjects.show', compact('subject', 'subjectTeacher'));
    }
}
