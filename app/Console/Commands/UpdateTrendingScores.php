<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;

class UpdateTrendingScores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:update-trending';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $description = 'Update trending scores for all published posts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating trending scores...');

        Post::where('status', 'published')
            ->where('published_at', '>=', now()->subDays(7)) // Only last 7 days for efficiency
            ->get()
            ->each(function ($post) {
                $post->updateTrendingScore();
            });

        $this->info('Scores updated successfully.');
    }
}
