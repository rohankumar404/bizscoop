<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function index()
    {
        $subscribers = Subscriber::latest()->paginate(50);
        return view('admin.newsletters.index', compact('subscribers'));
    }

    public function toggleStatus(Subscriber $subscriber)
    {
        $subscriber->update([
            'is_active' => !$subscriber->is_active,
            'unsubscribed_at' => $subscriber->is_active ? null : now(),
        ]);

        return redirect()->back()->with('success', 'Subscriber status updated.');
    }

    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();
        return redirect()->back()->with('success', 'Subscriber removed.');
    }
}
