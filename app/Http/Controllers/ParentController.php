<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\ExamResult;
use App\Models\Announcement;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Invoice;

class ParentController extends Controller
{
    public function dashboard()
    {
        $parent = auth()->user()->parentModel;
        $children = $parent->students;

        $stats = [];
        foreach ($children as $child) {
            $stats[$child->id] = [
                'name' => $child->user->name,
                'today_attendance' => Attendance::where('student_id', $child->id)
                    ->where('date', today())
                    ->first(),
                'unread_messages' => Message::where('receiver_id', auth()->id())
                    ->where('is_read', false)
                    ->count(),
                'unread_notifications' => Notification::where('user_id', auth()->id())
                    ->where('is_read', false)
                    ->count(),
            ];
        }

        return view('parent.dashboard', compact('children', 'stats'));
    }

    public function childDetail($childId)
    {
        $parent = auth()->user()->parentModel;
        $child = $parent->students()->findOrFail($childId);

        return view('parent.child_detail', compact('child'));
    }

    // View child's attendance
    public function childAttendance($childId)
    {
        $parent = auth()->user()->parentModel;
        $child = $parent->students()->findOrFail($childId);

        $attendances = Attendance::where('student_id', $childId)
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('parent.child_attendance', compact('child', 'attendances'));
    }

    // View child's grades
    public function childGrades($childId)
    {
        $parent = auth()->user()->parentModel;
        $child = $parent->students()->findOrFail($childId);

        $examResults = ExamResult::where('student_id', $childId)
            ->with('exam.subject', 'exam.classRoom')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('parent.child_grades', compact('child', 'examResults'));
    }

    // View child's invoices
    public function childInvoices($childId)
    {
        $parent = auth()->user()->parentModel;
        $child = $parent->students()->findOrFail($childId);

        $invoices = Invoice::where('student_id', $childId)
            ->with('createdBy')
            ->orderBy('due_date', 'desc')
            ->paginate(20);

        return view('parent.child_invoices', compact('child', 'invoices'));
    }

    // Announcements (public ones)
    public function announcements()
    {
        $announcements = Announcement::where('is_public', true)
            ->with('postedBy')
            ->orderBy('pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('parent.announcements', compact('announcements'));
    }

    // Messages
    public function messages()
    {
        $messages = Message::where('receiver_id', auth()->id())
            ->orWhere('sender_id', auth()->id())
            ->with('sender', 'receiver')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('parent.messages', compact('messages'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'subject' => $request->subject,
            'body' => $request->body,
            'is_read' => false,
        ]);

        return back()->with('success', 'Message sent successfully.');
    }

    // Notifications
    public function notifications()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Mark as read
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('parent.notifications', compact('notifications'));
    }
}
