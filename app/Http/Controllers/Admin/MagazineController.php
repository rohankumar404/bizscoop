<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Magazine;
use Illuminate\Http\Request;

class MagazineController extends Controller
{
    public function index()
    {
        $magazines = Magazine::latest('published_at')->paginate(15);
        return view('admin.magazines.index', compact('magazines'));
    }

    public function create()
    {
        return view('admin.magazines.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'issue_number' => 'nullable|string|max:50',
            'published_at' => 'required|date',
            'cover_image' => 'required|image|max:2048',
            'pdf_file' => 'required|file|mimes:pdf|max:51200', // 50MB max
        ]);

        $magazine = Magazine::create([
            'title' => $validated['title'],
            'issue_number' => $validated['issue_number'],
            'published_at' => $validated['published_at'],
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->hasFile('cover_image')) {
            $magazine->addMediaFromRequest('cover_image')->toMediaCollection('cover_image');
        }

        if ($request->hasFile('pdf_file')) {
            $magazine->addMediaFromRequest('pdf_file')->toMediaCollection('pdf_file');
        }

        return redirect()->route('admin.magazines.index')->with('success', 'Magazine issue published successfully.');
    }

    public function edit(Magazine $magazine)
    {
        return view('admin.magazines.form', compact('magazine'));
    }

    public function update(Request $request, Magazine $magazine)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'issue_number' => 'nullable|string|max:50',
            'published_at' => 'required|date',
            'cover_image' => 'nullable|image|max:2048',
            'pdf_file' => 'nullable|file|mimes:pdf|max:51200',
        ]);

        $magazine->update([
            'title' => $validated['title'],
            'issue_number' => $validated['issue_number'],
            'published_at' => $validated['published_at'],
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->hasFile('cover_image')) {
            $magazine->clearMediaCollection('cover_image');
            $magazine->addMediaFromRequest('cover_image')->toMediaCollection('cover_image');
        }

        if ($request->hasFile('pdf_file')) {
            $magazine->clearMediaCollection('pdf_file');
            $magazine->addMediaFromRequest('pdf_file')->toMediaCollection('pdf_file');
        }

        return redirect()->route('admin.magazines.index')->with('success', 'Magazine issue updated successfully.');
    }

    public function destroy(Magazine $magazine)
    {
        $magazine->delete();
        return redirect()->route('admin.magazines.index')->with('success', 'Magazine issue deleted successfully.');
    }
}
