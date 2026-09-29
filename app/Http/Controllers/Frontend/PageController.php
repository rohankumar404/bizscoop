<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\SeoService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about(SeoService $seo)
    {
        $seoData = $seo->forStaticPage('about-us', 'About Bizscoop | High-Integrity Business Journalism');
        return view('frontend.pages.about', compact('seoData'));
    }

    public function editorial(SeoService $seo)
    {
        $seoData = $seo->forStaticPage('editorial-standards', 'Editorial Standards | Bizscoop');
        return view('frontend.pages.editorial', compact('seoData'));
    }

    public function advertise(SeoService $seo)
    {
        $seoData = $seo->forStaticPage('advertise-with-us', 'Advertise With Us | Bizscoop');
        return view('frontend.pages.advertise', compact('seoData'));
    }

    public function careers(SeoService $seo)
    {
        $jobs = \App\Models\JobPosting::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        $emailsString = \App\Models\Setting::get('notification_emails', config('mail.from.address'));
        $emails       = array_filter(array_map('trim', explode(',', $emailsString)));
        $adminEmail   = !empty($emails) ? $emails[0] : config('mail.from.address');

        $seoData = $seo->forStaticPage('careers', 'Careers | Shape the Future of Business Media');
        return view('frontend.pages.careers', compact('jobs', 'adminEmail', 'seoData'));
    }

    public function contact(SeoService $seo)
    {
        $seoData = $seo->forStaticPage('contact-us', 'Contact Us | Bizscoop');
        return view('frontend.pages.contact', compact('seoData'));
    }

    public function privacy(SeoService $seo)
    {
        $seoData = $seo->forStaticPage('privacy-policy', 'Privacy Policy | Bizscoop');
        return view('frontend.pages.privacy', compact('seoData'));
    }
}
