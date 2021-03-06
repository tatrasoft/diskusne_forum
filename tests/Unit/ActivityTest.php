<?php

namespace Tests\Unit;

use App\Models\Activity;
use App\Models\Thread;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function test_it_records_activity_when_a_thread_is_created()
    {
        $this->be(factory('App\Models\User')->create());

        $thread = factory('App\Models\Thread')->create();

        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'subject_type' => 'App\Models\Thread'
        ]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }

    /** @test */
    function test_records_activity_when_a_reply_is_created()
    {
        $this->be(factory('App\Models\User')->create());

        $reply = factory('App\Models\Reply')->create();

        $this->assertEquals(2, Activity::count());
    }

    /** @test */
    function test_it_fetches_a_feed_for_any_user()
    {
        $this->be(factory('App\Models\User')->create());

        factory('App\Models\Thread', 2)->create(['user_id' => auth()->id()]);

        auth()->user()->activity()->first()->update(['created_at' => Carbon::now()->subWeek()]);

        $feed = Activity::feed(auth()->user());

        $this->assertTrue($feed->keys()->contains(
           Carbon::now()->format('Y-m-d')
        ));

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));
    }
}
