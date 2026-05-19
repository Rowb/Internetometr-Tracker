<?php

namespace Tests\Mocks;

use App\Exceptions\SpeedtestFailureException;
use App\Interfaces\SpeedtestProvider;
use App\Models\Speedtest;
use App\Utils\InternetometerTester;
use Exception;

class InternetometerTesterMocker implements SpeedtestProvider
{
    private bool $passes;

    public function __construct(bool $passes = true)
    {
        $this->passes = $passes;
    }

    public function run($output = null, $scheduled = true): Speedtest
    {
        $output = $output ?? $this->output();

        try {
            $data = json_decode($output, true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            throw new SpeedtestFailureException();
        }

        if (!$this->passes || !InternetometerTester::isOutputComplete($data)) {
            return Speedtest::factory()->create([
                'download' => 0,
                'upload' => 0,
                'ping' => 0,
                'failed' => true,
            ]);
        }

        return (new InternetometerTester())->run($output, $scheduled);
    }

    public function output()
    {
        if (!$this->passes) {
            return null;
        }

        return json_encode([
            'download_mbps' => 50.5,
            'upload_mbps' => 25.2,
            'latency_ms' => 12,
            'region' => 'Moscow',
            'isp' => 'Test ISP',
            'asn' => 12345,
            'test_url' => 'https://yandex.ru/internet',
            'ipv4' => '1.2.3.4',
        ]);
    }
}
