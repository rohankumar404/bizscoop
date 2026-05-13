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
        return view('frontend.pages.careers');
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
