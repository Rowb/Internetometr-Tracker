<?php

namespace Tests\Unit\Helpers\SpeedtestHelper;

use App\Utils\InternetometerTester;
use PHPUnit\Framework\TestCase;

class CheckOutputTest extends TestCase
{
    private InternetometerTester $speedtestProvider;

    public function setUp(): void
    {
        $this->speedtestProvider = new InternetometerTester();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGoodOutput()
    {
        $expected = [
            'download_mbps' => 100.5,
            'upload_mbps' => 50.2,
            'latency_ms' => 10,
        ];

        $this->assertTrue(InternetometerTester::isOutputComplete($expected));
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testBadOutput()
    {
        $expected = [
            'download_mbps' => 100.5,
            'latency_ms' => 10,
        ];

        $this->assertFalse(InternetometerTester::isOutputComplete($expected));
    }
}
