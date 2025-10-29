<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = ContactMessage::with('approver')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.contact_messages.index', compact('messages'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContactMessage $contactMessage)
    {
        return view('admin.contact_messages.edit', compact('contactMessage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContactMessage $contactMessage)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $contactMessage->update($validated);

        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'Pesan berhasil diperbarui.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactMessage $contactMessage)
    {
        return view('admin.contact_messages.show', compact('contactMessage'));
    }

    /**
     * Approve the message.
     */
    public function approve(ContactMessage $contactMessage)
    {
        $contactMessage->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'Pesan berhasil disetujui.');
    }

    /**
     * Reject the message.
     */
    public function reject(Request $request, ContactMessage $contactMessage)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $contactMessage->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'Pesan berhasil ditolak.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'Pesan berhasil dihapus.');
    }
}
