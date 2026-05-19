<?php

namespace Tests\Feature;

use App\Models\Poll;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PollTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_poll_appears_on_homepage()
    {
        // 1. Create an active poll
        $poll = Poll::create([
            'question' => 'What is your favorite framework?',
            'options' => [
                ['text' => 'Laravel', 'votes' => 10],
                ['text' => 'Symfony', 'votes' => 5],
            ],
            'is_active' => true,
        ]);

        // 2. Make request to homepage
        $response = $this->get('/');

        // 3. Assert poll question is visible
        $response->assertStatus(200);
        $response->assertSee('What is your favorite framework?');
        $response->assertSee('Laravel');
        $response->assertSee('Symfony');
    }

    public function test_can_vote_on_active_poll()
    {
        $poll = Poll::create([
            'question' => 'What is your favorite framework?',
            'options' => [
                ['text' => 'Laravel', 'votes' => 10],
                ['text' => 'Symfony', 'votes' => 5],
            ],
            'is_active' => true,
        ]);

        // Post a vote for Laravel (index 0)
        $response = $this->postJson(route('frontend.polls.vote', $poll->id), [
            'option_index' => 0,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'total_votes' => 16,
        ]);

        // Refresh and check database
        $poll->refresh();
        $this->assertEquals(11, $poll->options[0]['votes']);
        $this->assertEquals(16, $poll->total_votes);
    }

    public function test_prevent_double_voting()
    {
        $poll = Poll::create([
            'question' => 'What is your favorite framework?',
            'options' => [
                ['text' => 'Laravel', 'votes' => 10],
                ['text' => 'Symfony', 'votes' => 5],
            ],
            'is_active' => true,
        ]);

        // First vote
        $response1 = $this->postJson(route('frontend.polls.vote', $poll->id), [
            'option_index' => 0,
        ]);
        $response1->assertStatus(200);

        // Second vote (should fail session protection)
        $response2 = $this->postJson(route('frontend.polls.vote', $poll->id), [
            'option_index' => 0,
        ]);
        $response2->assertStatus(422);
        $response2->assertJson([
            'success' => false,
            'message' => 'You have already voted in this poll.',
        ]);
    }
}
