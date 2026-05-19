<?php

namespace Tests\Feature\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppVersionTest extends TestCase
{
    /**
     * Test the version CLI command
     *
     * @return void
     */
    public function testVersionCommand()
    {
        $response = $this->artisan('speedtest:version')
                         ->expectsOutput('Internetometer Tracker v' . config('speedtest.version'));
    }
}
