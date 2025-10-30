<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParentModel;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Services\LogService;

class ParentController extends Controller
{
    public function index(Request $request)
    {
        $query = ParentModel::with('user', 'students.user');

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('email', 'like', "%{$search}%");
                  });
            });
        }

        $parents = $query->paginate(20);

        return view('admin.parents.index', compact('parents'));
    }

    public function create()
    {
        return view('admin.parents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'relation_to_student' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'is_active' => true,
        ]);

        $user->assignRole('orang_tua');

        $parent = ParentModel::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'phone' => $request->phone,
            'relation_to_student' => $request->relation_to_student ?? 'Ayah/Ibu',
        ]);

        LogService::logCreate('Parent', $parent->id, $parent->toArray(), "Created parent: {$parent->name}");

        return redirect()->route('admin.parents.index')->with('success', 'Orang tua berhasil ditambahkan.');
    }

    public function show(ParentModel $parent)
    {
        $parent->load('user', 'students.user', 'students.classRoom');
        return view('admin.parents.show', compact('parent'));
    }

    public function edit(ParentModel $parent)
    {
        $parent->load('user');
        return view('admin.parents.edit', compact('parent'));
    }

    public function update(Request $request, ParentModel $parent)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($parent->user_id)],
            'phone' => 'nullable|string|max:20',
            'relation_to_student' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8',
            'is_active' => 'required|boolean',
        ]);

        $oldData = $parent->toArray();

        $parent->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'relation_to_student' => $request->relation_to_student,
        ]);

        $parent->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => $request->is_active,
        ]);

        if (!empty($request->password)) {
            $parent->user->update(['password' => Hash::make($request->password)]);
        }

        LogService::logUpdate('Parent', $parent->id, $oldData, $parent->toArray(), "Updated parent: {$parent->name}");

        return redirect()->route('admin.parents.index')->with('success', 'Data orang tua berhasil diperbarui.');
    }

    public function destroy(ParentModel $parent)
    {
        // Check if parent has students
        if ($parent->students()->exists()) {
            return redirect()->route('admin.parents.index')->with('error', 'Tidak dapat menghapus orang tua yang masih memiliki siswa.');
        }

        $oldData = $parent->toArray();

        // Remove role
        $parent->user->syncRoles([]);

        // Delete parent profile
        $parent->delete();

        // Delete user
        $parent->user->delete();

        LogService::logDelete('Parent', $parent->id, $oldData, "Deleted parent: {$parent->name}");

        return redirect()->route('admin.parents.index')->with('success', 'Orang tua berhasil dihapus.');
    }
}
