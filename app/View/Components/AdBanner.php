<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AdBanner extends Component
{
    public $position;
    public $ad;

    /**
     * Create a new component instance.
     */
    public function __construct($position)
    {
        $this->position = $position;
        $now = now();

        $this->ad = \App\Models\Ad::where('is_active', true)
            ->where(function($query) use ($position) {
                // MySQL JSON contains or LIKE fallback
                $query->whereJsonContains('position', $position)
                      ->orWhere('position', 'LIKE', '%"'.$position.'"%');
            })
            ->where(function($q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function($q) use ($now) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
            })
            ->inRandomOrder()
            ->first();

        // Increment views if ad exists
        if ($this->ad) {
            $this->ad->increment('views');
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if (!$this->ad) {
            return ''; // Return nothing if no ad is found
        }
        return view('components.ad-banner');
    }
}
