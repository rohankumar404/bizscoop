<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::latest()->paginate(15);
        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.videos.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'youtube_url' => 'required|url',
            'thumbnail' => 'required|image|max:2048',
        ]);

        $video = Video::create([
            'title' => $validated['title'],
            'youtube_url' => $validated['youtube_url'],
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->hasFile('thumbnail')) {
            $video->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnail');
        }

        return redirect()->route('admin.videos.index')->with('success', 'Video added successfully.');
    }

    public function edit(Video $video)
    {
        return view('admin.videos.form', compact('video'));
    }

    public function update(Request $request, Video $video)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'youtube_url' => 'required|url',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $video->update([
            'title' => $validated['title'],
            'youtube_url' => $validated['youtube_url'],
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->hasFile('thumbnail')) {
            $video->clearMediaCollection('thumbnail');
            $video->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnail');
        }

        return redirect()->route('admin.videos.index')->with('success', 'Video updated successfully.');
    }

    public function destroy(Video $video)
    {
        $video->delete();
        return redirect()->route('admin.videos.index')->with('success', 'Video deleted successfully.');
    }
}
