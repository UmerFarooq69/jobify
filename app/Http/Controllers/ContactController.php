<?php

namespace App\Http\Controllers;
use App\Models\Contact;
use Illuminate\Http\Request;


class ContactController extends Controller
{

    public function index() {
        $contacts = Contact::latest()->get();
        return view('contactus.index', compact('contacts'));
    }
    
    public function submit(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);
    
        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ]);
    
        return back()->with('success', 'Your message has been sent successfully!');
    }

    public function destroy(Contact $contact)
    {

        $contact->delete();

        return redirect()->route('contact.index')->with('success', 'Contact deleted successfully!');
    }
    public function toggleStatus($id)
    {
        $contact = Contact::findOrFail($id);
    
        $contact->status = $contact->status === 'pending' ? 'seen' : 'pending';
        $contact->save();
    
        return back()->with('success', 'Status updated successfully.');
    }
    
}