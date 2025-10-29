<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchoolProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $school = SchoolProfile::first();
        return view('admin.school_profiles.index', compact('school'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $school = SchoolProfile::findOrFail($id);
        return view('admin.school_profiles.edit', compact('school'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $school = SchoolProfile::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'headmaster_name' => 'required|string|max:255',
            'accreditation' => 'required|string|max:5',
            'vision' => 'required|string',
            'mission' => 'required|string',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'map_embed' => 'nullable|string',
            'hero_title' => 'nullable|string|max:255',
            'hero_description' => 'nullable|string|max:500',
            'school_description' => 'nullable|string',
            'total_achievements' => 'nullable|integer',
            'features' => 'nullable|array',
            'statistics' => 'nullable|array',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo_path')) {
            // Delete old logo if exists
            if ($school->logo_path && Storage::exists('public/' . $school->logo_path)) {
                Storage::delete('public/' . $school->logo_path);
            }

            $logoPath = $request->file('logo_path')->store('logos', 'public');
            $validated['logo_path'] = $logoPath;
        }

        // Handle JSON fields
        if ($request->has('features')) {
            $validated['features'] = json_encode($request->features);
        }

        if ($request->has('statistics')) {
            $validated['statistics'] = json_encode($request->statistics);
        }

        $school->update($validated);

        return redirect()->route('admin.school-profiles.index')
            ->with('success', 'Profil sekolah berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function about()
    {
        $school = SchoolProfile::first();

        // Hitung jumlah data
        $studentsCount = \App\Models\Student::count();
        $teachersCount = \App\Models\Teacher::count();
        $classesCount = \App\Models\ClassRoom::count();

        return view('public.about', compact('school', 'studentsCount', 'teachersCount', 'classesCount'));
    }

}
