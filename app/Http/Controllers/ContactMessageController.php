<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $pagination = config("pagination.per15", 15);
        $search = $request->search ?? "";
        $status = $request->status ?? null;

        $contacts = Contact::when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($pagination)
            ->withQueryString();

        $pendingCount = Contact::where('status', 'pending')->count();

        return view('dashboard.allcontacts', compact('contacts', 'pendingCount'));
    }

    public function update_status(Request $request, string $id)
    {
        $status = $request->input('status');
        $validStatuses = ['pending', 'read', 'responded'];

        if (!in_array($status, $validStatuses)) {
            return response()->json(['error' => 'Invalid status'], 400);
        }

        $contact = Contact::find($id);
        if (!$contact) {
            return response()->json(['error' => 'Contact not found'], 404);
        }

        $contact->status = $status;
        $contact->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    public function destroy(string $id)
    {
        $contact = Contact::find($id);
        if (!$contact) {
            return redirect()->back()->with('error', 'Message not found');
        }

        $contact->delete();
        return redirect()->back()->with('success', 'Message deleted successfully');
    }
}
