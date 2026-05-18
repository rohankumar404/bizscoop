<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('frontend.pages.about');
    }

    public function editorial()
    {
        return view('frontend.pages.editorial');
    }

    public function advertise()
    {
        return view('frontend.pages.advertise');
    }

    public function careers()
    {
        $jobs = \App\Models\JobPosting::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        $emailsString = \App\Models\Setting::get('notification_emails', config('mail.from.address'));
        $emails = array_filter(array_map('trim', explode(',', $emailsString)));
        $adminEmail = !empty($emails) ? $emails[0] : config('mail.from.address');

        return view('frontend.pages.careers', compact('jobs', 'adminEmail'));
    }

    public function contact()
    {
        return view('frontend.pages.contact');
    }

    public function privacy()
    {
        return view('frontend.pages.privacy');
    }
}
