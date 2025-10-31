<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Subject;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
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

        $query = Material::with(['subject', 'classRoom'])
            ->where('teacher_id', $teacher->id);

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
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $materials = $query->orderBy('created_at', 'desc')
                           ->paginate(20)
                           ->withQueryString();

        $subjects = Subject::whereHas('subjectTeachers', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->orderBy('name')->get();

        $classes = ClassRoom::whereHas('subjectTeachers', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->orderBy('name')->get();

        return view('teachers.materials.index', compact('materials', 'subjects', 'classes'));
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

        return view('teachers.materials.create', compact('subjects', 'classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $teacher = auth()->user()->teacher;

        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'class_id' => ['required', 'exists:class_rooms,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file_path' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,gif,mp4,avi,mov', 'max:51200'],
            'is_published' => ['boolean'],
        ]);

        // Check if teacher is assigned to this subject/class combination
        $isAssigned = $teacher->subjectTeachers()
            ->where('subject_id', $data['subject_id'])
            ->where('class_id', $data['class_id'])
            ->exists();

        if (!$isAssigned) {
            return back()
                ->withInput()
                ->with('error', 'Anda tidak memiliki akses untuk membuat materi pada mata pelajaran dan kelas ini.');
        }

        $data['teacher_id'] = $teacher->id;

        // Handle file upload
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('materials', $filename, 'public');
            $data['file_path'] = $path;
        }

        try {
            Material::create($data);

            return redirect()
                ->route('teachers.materials.index')
                ->with('success', 'Materi berhasil dibuat.');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat materi. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        // Check access permission
        if ($material->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $material->load(['subject', 'classRoom']);

        return view('teachers.materials.show', compact('material'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Material $material)
    {
        // Check access permission
        if ($material->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $teacher = auth()->user()->teacher;

        $subjects = Subject::whereHas('subjectTeachers', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->orderBy('name')->get();

        $classes = ClassRoom::whereHas('subjectTeachers', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->orderBy('name')->get();

        return view('teachers.materials.edit', compact('material', 'subjects', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material)
    {
        // Check access permission
        if ($material->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'class_id' => ['required', 'exists:class_rooms,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file_path' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,gif,mp4,avi,mov', 'max:51200'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        // Handle file upload
        if ($request->hasFile('file_path')) {
            // Delete old file if exists
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }

            $file = $request->file('file_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('materials', $filename, 'public');
            $data['file_path'] = $path;
        }

        // Handle is_published checkbox
        $data['is_published'] = $request->has('is_published') ? 1 : 0;

        try {
            $material->update($data);

            return redirect()
                ->route('teachers.materials.index')
                ->with('success', 'Materi berhasil diperbarui.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui materi. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material)
    {
        // Check access permission
        if ($material->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        try {
            // Delete file if exists
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }

            $material->delete();

            return redirect()
                ->route('teachers.materials.index')
                ->with('success', 'Materi berhasil dihapus.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus materi. Silakan coba lagi.');
        }
    }

    /**
     * Download material file
     */
    public function download(Material $material)
    {
        // Check access permission
        if ($material->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        if (!$material->file_path || !Storage::disk('public')->exists($material->file_path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($material->file_path);
    }
}
