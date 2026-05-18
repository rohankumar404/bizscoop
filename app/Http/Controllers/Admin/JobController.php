<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobController extends Controller
{
    public function index()
    {
        $jobs = JobPosting::orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('admin.jobs.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $count = 1;
        while (JobPosting::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        JobPosting::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'location' => $validated['location'],
            'type' => $validated['type'],
            'description' => $validated['description'],
            'is_active' => $request->has('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.jobs.index')->with('success', 'Job posting created successfully.');
    }

    public function edit(JobPosting $job)
    {
        return view('admin.jobs.form', compact('job'));
    }

    public function update(Request $request, JobPosting $job)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        $slug = $job->slug;
        if ($job->title !== $validated['title']) {
            $slug = Str::slug($validated['title']);
            $originalSlug = $slug;
            $count = 1;
            while (JobPosting::where('slug', $slug)->where('id', '!=', $job->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
        }

        $job->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'location' => $validated['location'],
            'type' => $validated['type'],
            'description' => $validated['description'],
            'is_active' => $request->has('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.jobs.index')->with('success', 'Job posting updated successfully.');
    }

    public function destroy(JobPosting $job)
    {
        $job->delete();
        return redirect()->route('admin.jobs.index')->with('success', 'Job posting deleted successfully.');
    }
}
