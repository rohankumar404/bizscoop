<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function index()
    {
        $polls = Poll::latest()->paginate(15);
        return view('admin.polls.index', compact('polls'));
    }

    public function create()
    {
        return view('admin.polls.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
        ]);

        $optionTexts = $request->input('options', []);
        $options = [];

        foreach ($optionTexts as $text) {
            $text = trim($text);
            if (empty($text)) continue;
            
            $options[] = [
                'text' => $text,
                'votes' => 0
            ];
        }

        $poll = Poll::create([
            'question' => $request->input('question'),
            'options' => $options,
            'is_active' => $request->has('is_active'),
        ]);

        if ($poll->is_active) {
            Poll::where('id', '!=', $poll->id)->update(['is_active' => false]);
        }

        return redirect()->route('admin.polls.index')->with('success', 'Poll created successfully.');
    }

    public function edit(Poll $poll)
    {
        return view('admin.polls.form', compact('poll'));
    }

    public function update(Request $request, Poll $poll)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
        ]);

        $optionTexts = $request->input('options', []);
        $options = [];

        // Preserve existing vote counts for options with matching text
        $existingOptions = $poll->options ?? [];
        $existingVotesMap = [];
        foreach ($existingOptions as $opt) {
            if (isset($opt['text']) && isset($opt['votes'])) {
                $existingVotesMap[$opt['text']] = (int)$opt['votes'];
            }
        }

        foreach ($optionTexts as $text) {
            $text = trim($text);
            if (empty($text)) continue;

            $votes = $existingVotesMap[$text] ?? 0;
            $options[] = [
                'text' => $text,
                'votes' => $votes
            ];
        }

        $poll->update([
            'question' => $request->input('question'),
            'options' => $options,
            'is_active' => $request->has('is_active'),
        ]);

        if ($poll->is_active) {
            Poll::where('id', '!=', $poll->id)->update(['is_active' => false]);
        }

        return redirect()->route('admin.polls.index')->with('success', 'Poll updated successfully.');
    }

    public function destroy(Poll $poll)
    {
        $poll->delete();
        return redirect()->route('admin.polls.index')->with('success', 'Poll deleted successfully.');
    }

    public function toggleActive(Poll $poll)
    {
        $newStatus = !$poll->is_active;

        if ($newStatus) {
            Poll::where('id', '!=', $poll->id)->update(['is_active' => false]);
        }

        $poll->update(['is_active' => $newStatus]);

        $statusMessage = $newStatus ? 'activated' : 'deactivated';
        return redirect()->route('admin.polls.index')->with('success', "Poll successfully {$statusMessage}.");
    }
}
