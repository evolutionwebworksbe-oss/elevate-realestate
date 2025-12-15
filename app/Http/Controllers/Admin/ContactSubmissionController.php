<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;

class ContactSubmissionController extends Controller
{
    public function index()
    {
        $submissions = ContactSubmission::with('property')
            ->orderBy('submitted_at', 'desc')
            ->paginate(50);
        
        return view('admin.contact-submissions.index', compact('submissions'));
    }
}