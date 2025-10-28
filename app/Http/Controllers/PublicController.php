<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolProfile;
use App\Models\Announcement;
use App\Models\Gallery;

class PublicController extends Controller
{
    public function home()
    {
        $school = SchoolProfile::first();
        $announcements = Announcement::where('is_public', true)
            ->orderBy('pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('public.home', compact('school', 'announcements'));
    }

    public function about()
    {
        $school = SchoolProfile::first();
        return view('public.about', compact('school'));
    }

    public function announcements()
    {
        $announcements = Announcement::where('is_public', true)
            ->with('postedBy')
            ->orderBy('pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('public.announcements', compact('announcements'));
    }

    public function gallery()
    {
        $galleries = Gallery::where('is_public', true)
            ->paginate(20);

        return view('public.gallery', compact('galleries'));
    }

    public function contact()
    {
        $school = SchoolProfile::first();
        return view('public.contact', compact('school'));
    }
}
