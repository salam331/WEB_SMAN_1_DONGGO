<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolProfile;
use App\Models\Announcement;
use App\Models\Gallery;
use App\Models\ContactMessage;

class PublicController extends Controller
{
    public function home()
    {
        $school = SchoolProfile::first();
        $announcements = Announcement::where('is_published', true)
            ->where(function($q) {
                $q->where('target_audience', 'all')
                  ->orWhere('target_audience', 'public');
            })
            ->orderBy('pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('public.home', compact('school', 'announcements'));
    }

    public function about()
    {
        $school = SchoolProfile::first();
        if ($school) {
            $school->features = json_decode($school->features, true);
            $school->statistics = json_decode($school->statistics, true);
        }

        // Hitung jumlah siswa, guru, dan kelas untuk statistik
        $studentsCount = \App\Models\Student::count();
        $teachersCount = \App\Models\Teacher::count();
        $classesCount = \App\Models\ClassRoom::count();

        return view('public.about', compact('school', 'studentsCount', 'teachersCount', 'classesCount'));
    }

    public function announcements()
    {
        $announcements = Announcement::where('is_published', true)
            ->where(function($q) {
                $q->where('target_audience', 'all')
                  ->orWhere('target_audience', 'public');
            })
            ->with('postedBy')
            ->orderBy('pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('public.announcements', compact('announcements'));
    }

    public function gallery()
    {
        $school = SchoolProfile::first();
        $galleries = Gallery::where('is_public', true)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('public.gallery', compact('galleries', 'school'));
    }

    public function contact()
    {
        $school = SchoolProfile::first();
        return view('public.contact', compact('school'));
    }

    public function storeContactMessage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        ContactMessage::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pesan Anda telah dikirim dan akan diperiksa oleh admin.'
        ]);
    }

    public function showAnnouncement($id)
    {
        $announcement = Announcement::with('postedBy')->findOrFail($id);

        return view('public.announcement-detail', compact('announcement'));
    }

    public function getAnnouncementDetail($id)
    {
        $announcement = Announcement::with('postedBy')->findOrFail($id);

        return response()->json([
            'id' => $announcement->id,
            'title' => $announcement->title,
            'content' => nl2br(e($announcement->content)),
            'date' => $announcement->created_at->format('d M Y'),
            'author' => $announcement->postedBy->name ?? 'Admin',
        ]);
    }


}
