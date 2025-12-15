<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function index()
    {
        $subscriptions = Newsletter::orderBy('subscribed_at', 'desc')->paginate(50);
        return view('admin.newsletters.index', compact('subscriptions'));
    }

    public function destroy(Newsletter $newsletter)
    {
        $newsletter->delete();
        return back()->with('success', 'Subscription deleted successfully');
    }

    public function export()
    {
        $subscriptions = Newsletter::orderBy('subscribed_at', 'desc')->get();
        
        $filename = 'newsletter_subscriptions_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($subscriptions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Email', 'Subscribed At']);

            foreach ($subscriptions as $subscription) {
                fputcsv($file, [
                    $subscription->email,
                    $subscription->subscribed_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}