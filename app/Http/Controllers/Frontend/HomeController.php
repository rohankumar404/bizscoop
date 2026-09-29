<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Services\SeoService;

class HomeController extends Controller
{
    /**
     * Display the homepage with video highlights and centralized SEO data.
     */
    public function index(SeoService $seo)
    {
        $videos  = Video::where('is_active', true)->latest()->get();
        $seoData = $seo->forHomepage();

        return view('welcome', compact('videos', 'seoData'));
    }
}
