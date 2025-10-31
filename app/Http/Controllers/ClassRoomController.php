<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin')->only(['create','store','edit','update','destroy']);
    }

    public function index(Request $request)
    {
        $query = ClassRoom::with(['homeroomTeacher.user', 'students'])
            ->withCount('students');

        // Search functionality
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('level', 'like', "%{$search}%")
                  ->orWhereHas('homeroomTeacher.user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by level
        if ($request->level) {
            $query->where('level', $request->level);
        }

        $classes = $query->orderBy('level')
                        ->orderBy('name')
                        ->paginate(20)
                        ->withQueryString();

        $levels = ClassRoom::distinct()->pluck('level')->sort();
        
        return view('admin.classes.index', compact('classes', 'levels'));
    }

    public function create()
    {
        $teachers = Teacher::with('user')
            ->whereDoesntHave('homeroomClass') // Only show teachers without homeroom class
            ->orderBy('nip')
            ->get();

        return view('admin.classes.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:class_rooms,name'],
            'level' => ['required', 'integer', 'between:10,12'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:50'],
            'room' => ['nullable', 'string', 'max:255'],
            'homeroom_teacher_id' => [
                'nullable',
                'exists:teachers,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $hasHomeroom = ClassRoom::where('homeroom_teacher_id', $value)->exists();
                        if ($hasHomeroom) {
                            $fail('Guru yang dipilih sudah menjadi wali kelas.');
                        }
                    }
                },
            ],
        ]);

        try {
            ClassRoom::create([
                'name' => $data['name'],
                'level' => $data['level'],
                'capacity' => $data['capacity'] ?? 30,
                'room' => $data['room'] ?? null,
                'homeroom_teacher_id' => $data['homeroom_teacher_id'] ?? null,
            ]);

            return redirect()
                ->route('admin.classes')
                ->with('success', 'Kelas berhasil dibuat.');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat kelas. Silakan coba lagi.');
        }
    }

    public function edit(ClassRoom $classRoom)
    {
        // Get available teachers (current homeroom teacher + teachers without homeroom)
        $teachers = Teacher::with('user')
            ->where(function($query) use ($classRoom) {
                $query->whereDoesntHave('homeroomClass')
                      ->orWhere('id', $classRoom->homeroom_teacher_id);
            })
            ->orderBy('nip')
            ->get();

        return view('admin.classes.edit', compact('classRoom', 'teachers'));
    }

    public function update(Request $request, ClassRoom $classRoom)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:class_rooms,name,' . $classRoom->id
            ],
            'level' => ['required', 'integer', 'between:10,12'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:50'],
            'room' => ['nullable', 'string', 'max:255'],
            'homeroom_teacher_id' => [
                'nullable',
                'exists:teachers,id',
                function ($attribute, $value, $fail) use ($classRoom) {
                    if ($value && $value !== $classRoom->homeroom_teacher_id) {
                        $hasHomeroom = ClassRoom::where('homeroom_teacher_id', $value)
                            ->where('id', '!=', $classRoom->id)
                            ->exists();
                        if ($hasHomeroom) {
                            $fail('Guru yang dipilih sudah menjadi wali kelas.');
                        }
                    }
                },
            ],
        ]);

        try {
            // Check if reducing capacity below current student count
            if (!empty($data['capacity']) && 
                $data['capacity'] < $classRoom->students()->count()) {
                return back()
                    ->withInput()
                    ->withErrors(['capacity' => 'Kapasitas tidak boleh lebih kecil dari jumlah siswa saat ini.']);
            }

            $classRoom->update([
                'name' => $data['name'],
                'level' => $data['level'],
                'capacity' => $data['capacity'] ?? $classRoom->capacity,
                'room' => $data['room'] ?? $classRoom->room,
                'homeroom_teacher_id' => $data['homeroom_teacher_id'] ?? null,
            ]);

            return redirect()
                ->route('admin.classes')
                ->with('success', 'Kelas berhasil diperbarui.');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui kelas. Silakan coba lagi.');
        }
    }

    public function destroy(ClassRoom $classRoom)
    {
        try {
            // Check if class has students
            if ($classRoom->students()->exists()) {
                return back()->with('error', 
                    'Tidak dapat menghapus kelas karena masih memiliki siswa. ' .
                    'Pindahkan semua siswa terlebih dahulu.');
            }

            // Check if class has related data
            if ($classRoom->schedules()->exists() || 
                $classRoom->exams()->exists() || 
                $classRoom->materials()->exists()) {
                return back()->with('error',
                    'Tidak dapat menghapus kelas karena memiliki data terkait ' .
                    '(jadwal/ujian/materi). Hapus data terkait terlebih dahulu.');
            }

            $classRoom->delete();
            return redirect()
                ->route('admin.classes')
                ->with('success', 'Kelas berhasil dihapus.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus kelas. Silakan coba lagi.');
        }
    }
}
