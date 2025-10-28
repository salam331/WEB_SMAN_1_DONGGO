<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Subject::query();

        // Search functionality
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $subjects = $query->withCount(['subjectTeachers', 'schedules'])
                          ->orderBy('code')
                          ->paginate(20)
                          ->withQueryString();

        return view('admin.subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.subjects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:10', 'unique:subjects,code'],
            'name' => ['required', 'string', 'max:255', 'unique:subjects,name'],
            'kkm' => ['required', 'integer', 'between:0,100'],
        ]);

        try {
            Subject::create($data);

            return redirect()
                ->route('admin.subjects.index')
                ->with('success', 'Mata pelajaran berhasil dibuat.');

        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database constraint violations (unique constraints, etc.)
            if ($e->getCode() == 23000) { // Integrity constraint violation
                return back()
                    ->withInput()
                    ->with('error', 'Kode atau nama mata pelajaran sudah ada. Silakan gunakan yang berbeda.');
            }

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan database. Silakan coba lagi.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat mata pelajaran. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        $subject->load(['subjectTeachers.teacher.user', 'subjectTeachers.classRoom', 'materials', 'exams']);

        return view('admin.subjects.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        $teachers = \App\Models\Teacher::join('users', 'teachers.user_id', '=', 'users.id')
            ->select('teachers.*')
            ->orderBy('users.name', 'asc')
            ->get();
        return view('admin.subjects.edit', compact('subject', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'code' => [
                'required',
                'string',
                'max:10',
                'unique:subjects,code,' . $subject->id
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:subjects,name,' . $subject->id
            ],
            'kkm' => ['required', 'integer', 'between:0,100'],
            'material_id' => ['nullable', 'exists:materials,id'],
            'teacher_id' => ['nullable', 'exists:teachers,id'],
        ]);

        try {
            $subject->update($data);

            return redirect()
                ->route('admin.subjects.index')
                ->with('success', 'Mata pelajaran berhasil diperbarui.');

        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database constraint violations (unique constraints, etc.)
            if ($e->getCode() == 23000) { // Integrity constraint violation
                return back()
                    ->withInput()
                    ->with('error', 'Kode atau nama mata pelajaran sudah digunakan mata pelajaran lain. Silakan gunakan yang berbeda.');
            }

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan database. Silakan coba lagi.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui mata pelajaran. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        try {
            // Check if subject has related data
            if ($subject->subjectTeachers()->exists() ||
                $subject->materials()->exists() ||
                $subject->exams()->exists()) {
                return back()->with('error',
                    'Tidak dapat menghapus mata pelajaran karena memiliki data terkait ' .
                    '(pengajar, materi, atau ujian). Hapus data terkait terlebih dahulu.');
            }

            $subject->delete();
            return redirect()
                ->route('admin.subjects.index')
                ->with('success', 'Mata pelajaran berhasil dihapus.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus mata pelajaran. Silakan coba lagi.');
        }
    }
}
