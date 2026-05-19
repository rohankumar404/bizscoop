<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable = [
        'question',
        'options',
        'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean',
    ];

    public function getTotalVotesAttribute(): int
    {
        if (is_array($this->options)) {
            return array_sum(array_column($this->options, 'votes'));
        }
        return 0;
    }

    public function getOptionsWithPercentagesAttribute(): array
    {
        $total = $this->total_votes;
        $options = $this->options ?? [];

        return array_map(function ($opt) use ($total) {
            $votes = isset($opt['votes']) ? (int)$opt['votes'] : 0;
            return [
                'text' => $opt['text'] ?? '',
                'votes' => $votes,
                'percentage' => $total > 0 ? round(($votes / $total) * 100) : 0,
            ];
        }, $options);
    }
}
