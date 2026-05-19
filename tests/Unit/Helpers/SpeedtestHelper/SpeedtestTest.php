<?php

namespace Tests\Unit\Helpers\SpeedtestHelper;

use App\Exceptions\SpeedtestFailureException;
use App\Helpers\SpeedtestHelper;
use App\Utils\InternetometerTester;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Mocks\InternetometerTesterMocker;
use Tests\TestCase;

class SpeedtestTest extends TestCase
{
    use RefreshDatabase;

    private $output;

    private InternetometerTester $speedtestProvider;

    public function setUp(): void
    {
        parent::setUp();

        $this->speedtestProvider = new InternetometerTester();

        $this->output = (new InternetometerTesterMocker())->output();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testRunSpeedtestWithExistingOutput()
    {
        $output = json_decode($this->output, true);

        $test = $this->speedtestProvider->run($this->output);

        $this->assertEquals($output['latency_ms'], $test->ping);
        $this->assertEquals($output['download_mbps'], $test->download);
        $this->assertEquals($output['upload_mbps'], $test->upload);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testInvaidJson()
    {
        $this->expectException(SpeedtestFailureException::class);

        $json = '{hi: hi}';

        $o = $this->speedtestProvider->run($json);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testIncompleteJson()
    {
        $this->expectException(SpeedtestFailureException::class);

        $json = '{"hi": "hi"}';

        $o = $this->speedtestProvider->run($json);
    }
}
