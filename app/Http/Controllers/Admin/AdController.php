<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    public function index()
    {
        $ads = Ad::latest()->paginate(20);
        return view('admin.ads.index', compact('ads'));
    }

    public function create()
    {
        return view('admin.ads.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'position' => 'required|string',
            'type' => 'required|in:image,code',
            'content' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'link' => 'nullable|url',
            'expires_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('ads', 'public');
        }

        Ad::create($data);

        return redirect()->route('admin.ads.index')->with('success', 'Advertisement created.');
    }

    public function edit(Ad $ad)
    {
        return view('admin.ads.edit', compact('ad'));
    }

    public function update(Request $request, Ad $ad)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'position' => 'required|string',
            'type' => 'required|in:image,code',
            'content' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'link' => 'nullable|url',
            'expires_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($ad->image) Storage::disk('public')->delete($ad->image);
            $data['image'] = $request->file('image')->store('ads', 'public');
        }

        $ad->update($data);

        return redirect()->route('admin.ads.index')->with('success', 'Advertisement updated.');
    }

    public function destroy(Ad $ad)
    {
        if ($ad->image) Storage::disk('public')->delete($ad->image);
        $ad->delete();
        return redirect()->route('admin.ads.index')->with('success', 'Advertisement deleted.');
    }
}
