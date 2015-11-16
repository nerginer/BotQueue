<?php

use App\Models\Job;
use App\Handlers\Api\Jobs As JobsHandler;

class JobsStatusTest extends AuthTestCase
{
    public function setUp()
    {
        parent::setUp();

        factory(Job::class, 3)->create(['status' => 'available']);
        factory(Job::class, 5)->create(['status' => 'taken']);
    }

    /** @test */
    public function it_returns_all_results_if_not_limited()
    {
        /** @var JobsHandler $api */
        $api = api('jobs');
        $jobs = $api->get();
        $this->assertTrue($jobs->isSuccessful());
        $this->assertEquals(8, $jobs->count());
        $this->assertCount(8, $jobs);
    }

    /** @test */
    public function it_returns_available_jobs()
    {
        /** @var JobsHandler $api */
        $api = api('jobs');
        $jobs = $api->status('available')->get();
        $this->assertTrue($jobs->isSuccessful());
        $this->assertEquals(3, $jobs->count());
        $this->assertCount(3, $jobs);
    }

    /** @test */
    public function it_returns_taken_jobs()
    {
        /** @var JobsHandler $api */
        $api = api('jobs');
        $jobs = $api->status('taken')->get();
        $this->assertTrue($jobs->isSuccessful());
        $this->assertEquals(5, $jobs->count());
        $this->assertCount(5, $jobs);
    }

    /** @test */
    public function it_can_limit_using_an_array()
    {
        /** @var JobsHandler $api */
        $api = api('jobs');
        $jobs = $api->status(['available', 'taken'])->get();
        $this->assertTrue($jobs->isSuccessful());
        $this->assertEquals(8, $jobs->count());
        $this->assertCount(8, $jobs);
    }

    /** @test */
    public function it_can_limit_using_method_chaining()
    {
        /** @var JobsHandler $api */
        $api = api('jobs');
        $jobs = $api->status('available')->status('taken')->get();
        $this->assertTrue($jobs->isSuccessful());
        $this->assertEquals(8, $jobs->count());
        $this->assertCount(8, $jobs);
    }
}