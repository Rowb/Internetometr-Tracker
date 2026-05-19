<?php

namespace Tests\Feature;

use App\Helpers\SpeedtestHelper;
use App\Interfaces\SpeedtestProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Mocks\InternetometerTesterMocker;
use Tests\TestCase;

class SpeedtestTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->singleton(
            SpeedtestProvider::class,
            function () {
                return new InternetometerTesterMocker();
            }
        );
    }

    /**
     * Runs a speedtest
     *
     * @test
     * @return void
     */
    public function runSpeedtest()
    {
        $data = SpeedtestHelper::runSpeedtest();

        $this->assertArrayHasKey('ping', $data);
        $this->assertArrayHasKey('download', $data);
        $this->assertArrayHasKey('upload', $data);
    }
}
