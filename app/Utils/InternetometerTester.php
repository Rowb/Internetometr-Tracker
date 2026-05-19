<?php

namespace App\Utils;

use App\Exceptions\SpeedtestFailureException;
use App\Interfaces\SpeedtestProvider;
use App\Models\Speedtest;
use Cache;
use Exception;
use JsonException;
use Log;

class InternetometerTester implements SpeedtestProvider
{
    public function run($output = false, $scheduled = true): Speedtest
    {
        if ($output === false) {
            $output = $this->output();
        }

        if ($output === false || $output === null || trim($output) === '') {
            $this->recordFailure($scheduled);
            throw new SpeedtestFailureException('Empty internetometer output');
        }

        try {
            $data = json_decode($output, true, 512, JSON_THROW_ON_ERROR);

            if (!self::isOutputComplete($data)) {
                $this->recordFailure($scheduled);
                throw new SpeedtestFailureException($output);
            }

            $serverHost = null;
            if (!empty($data['test_url'])) {
                $parsed = parse_url($data['test_url']);
                $serverHost = $parsed['host'] ?? $data['test_url'];
            }

            $serverName = $data['region'] ?? 'Yandex Internetometer';
            if (!empty($data['isp'])) {
                $serverName .= ' / ' . $data['isp'];
            }

            $test = Speedtest::create([
                'ping' => (float) $data['latency_ms'],
                'download' => (float) $data['download_mbps'],
                'upload' => (float) $data['upload_mbps'],
                'server_id' => isset($data['asn']) ? (int) $data['asn'] : null,
                'server_name' => $serverName,
                'server_host' => $serverHost,
                'url' => $data['test_url'] ?? null,
                'scheduled' => $scheduled,
            ]);
        } catch (JsonException $e) {
            Log::error('Failed to parse internetometer JSON');
            Log::error($output);
            $this->recordFailure($scheduled);
            throw new SpeedtestFailureException($output);
        } catch (SpeedtestFailureException $e) {
            throw $e;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $this->recordFailure($scheduled);
            throw new SpeedtestFailureException($output);
        }

        Cache::flush();

        return $test;
    }

    public function output()
    {
        $binPath = app_path('Bin/internetometer');
        $homePrefix = config('speedtest.home') . ' && ';
        $lang = config('speedtest.internetometer_lang', 'ru');

        $command = $homePrefix . escapeshellarg($binPath)
            . ' --json --all --lang ' . escapeshellarg($lang)
            . ' 2>/dev/null';

        return shell_exec($command);
    }

    /**
     * Checks that the internetometer JSON output is complete/valid
     *
     * @param array $output
     * @return boolean
     */
    public static function isOutputComplete($output)
    {
        if (!is_array($output)) {
            return false;
        }

        foreach (['download_mbps', 'upload_mbps', 'latency_ms'] as $key) {
            if (!isset($output[$key]) || !is_numeric($output[$key])) {
                return false;
            }
        }

        return true;
    }

    private function recordFailure(bool $scheduled): void
    {
        Speedtest::create([
            'ping' => 0,
            'upload' => 0,
            'download' => 0,
            'failed' => true,
            'scheduled' => $scheduled,
        ]);
    }
}
