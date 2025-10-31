<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Subject;
use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,guru');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Material::with(['subject', 'teacher.user', 'classRoom']);

        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if (!auth()->user()->hasRole('admin')) {
            $query->where('teacher_id', auth()->user()->teacher->id);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $materials = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $subjects = Subject::orderBy('name')->get();
        $classes = ClassRoom::orderBy('name')->get();

        return view('admin.materials.index', compact('materials', 'subjects', 'classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua mata pelajaran
        $subjects = Subject::orderBy('name', 'asc')->get();

        // Ambil semua kelas
        $class_rooms = ClassRoom::orderBy('name', 'asc')->get();

        // Ambil semua guru dengan relasi user
        $teachers = \App\Models\Teacher::join('users', 'teachers.user_id', '=', 'users.id')
            ->select('teachers.*')
            ->orderBy('users.name', 'asc')
            ->get();

        // Kirim ke view
        return view('admin.materials.create', compact('subjects', 'class_rooms', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi dasar
        $rules = [
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:class_rooms,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_path' => 'nullable|file',
        ];

        // Admin bisa memilih teacher_id, guru otomatis
        if (auth()->user()->hasRole('admin')) {
            $rules['teacher_id'] = 'required|exists:teachers,id';
        }

        $validated = $request->validate($rules);

        // Jika user guru, ambil teacher_id otomatis
        if (auth()->user()->hasRole('guru')) {
            $teacher = \App\Models\Teacher::where('user_id', auth()->id())->firstOrFail();
            $validated['teacher_id'] = $teacher->id;
        }

        // Upload file jika ada
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('materials', $filename, 'public');
            $validated['file_path'] = 'materials/' . $filename;
        }

        \App\Models\Material::create($validated);

        return redirect()->route('admin.materials.index')->with('success', 'Materi berhasil ditambahkan!');
    }



    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        if (!auth()->user()->hasRole('admin') && $material->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $material->load(['subject', 'teacher.user', 'classRoom']);

        return view('admin.materials.show', compact('material'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Material $material)
    {
        if (!auth()->user()->hasRole('admin') && $material->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $subjects = Subject::orderBy('name')->get();
        $classes = ClassRoom::orderBy('name')->get();

        return view('admin.materials.edit', compact('material', 'subjects', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material)
    {
        if (!auth()->user()->hasRole('admin') && $material->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'class_id' => ['required', 'exists:class_rooms,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file_path' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,gif,mp4,avi,mov', 'max:51200'],
        ]);

        if ($request->hasFile('file_path')) {
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

        $material->update($data);

        return redirect()->route('admin.materials.index')
            ->with('success', 'Materi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material)
    {
        if (!auth()->user()->hasRole('admin') && $material->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return redirect()->route('admin.materials.index')
            ->with('success', 'Materi berhasil dihapus.');
    }

    /**
     * Download material file
     */
    public function download(Material $material)
    {
        if (
            !auth()->user()->hasRole('admin') &&
            !auth()->user()->hasRole('guru') &&
            $material->class_id !== auth()->user()->student->class_id
        ) {
            abort(403);
        }

        if (!$material->file_path || !Storage::disk('public')->exists($material->file_path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($material->file_path);
    }
}
