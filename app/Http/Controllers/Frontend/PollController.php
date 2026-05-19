<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function vote(Request $request, Poll $poll)
    {
        if (session()->has("voted_poll_{$poll->id}")) {
            return response()->json([
                'success' => false,
                'message' => 'You have already voted in this poll.'
            ], 422);
        }

        $request->validate([
            'option_index' => 'required|integer',
        ]);

        $optionIndex = (int) $request->input('option_index');
        $options = $poll->options;

        if (!isset($options[$optionIndex])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid choice option selected.'
            ], 400);
        }

        $options[$optionIndex]['votes'] = (isset($options[$optionIndex]['votes']) ? (int)$options[$optionIndex]['votes'] : 0) + 1;
        $poll->options = $options;
        $poll->save();

        session()->put("voted_poll_{$poll->id}", true);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for voting!',
            'options' => $poll->options_with_percentages,
            'total_votes' => $poll->total_votes
        ]);
    }
}
