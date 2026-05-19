<?php

namespace App\View\Components;

use App\Models\Poll;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ReaderPoll extends Component
{
    public $poll;
    public $voted;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->poll = Poll::where('is_active', true)->first();
        $this->voted = $this->poll ? session()->has("voted_poll_{$this->poll->id}") : false;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.reader-poll');
    }
}
