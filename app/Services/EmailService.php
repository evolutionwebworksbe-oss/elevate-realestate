<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class EmailService
{
    public function sendContactEmail($data)
    {
        try {
            Mail::to(config('mail.contact_to', 'info@elevaterealestate.sr'))
                ->send(new ContactFormMail($data));
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Email sending error: ' . $e->getMessage());
            return false;
        }
    }
}