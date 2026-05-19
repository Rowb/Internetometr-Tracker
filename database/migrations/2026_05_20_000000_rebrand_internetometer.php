<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

class RebrandInternetometer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Setting::where('name', 'app_name')->update(['value' => 'Internetometer Tracker']);

        $descriptions = [
            'schedule' => '<p class="d-inline">Расписание замеров скорости (CRON). </p><a href="https://crontab.guru/" target="_blank" rel="noopener noreferrer">Подсказка по формату</a>',
            'speedtest_notifications' => 'Уведомление после каждого замера Internetometer',
            'speedtest_overview_notification' => 'Ежедневная сводка замеров',
            'speedtest_provider' => 'Провайдер замеров (Яндекс Internetometer)',
            'influxdb_enabled' => 'Интеграция InfluxDB для замеров Internetometer',
            'healthchecks_enabled' => 'Интеграция healthchecks.io для замеров Internetometer',
            'show_average' => 'Средние значения замеров в виджетах',
            'show_max' => 'Максимальные значения замеров в виджетах',
            'show_min' => 'Минимальные значения замеров в виджетах',
        ];

        foreach ($descriptions as $name => $description) {
            Setting::where('name', $name)->update(['description' => $description]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Setting::where('name', 'app_name')->update(['value' => 'Speedtest Tracker']);
    }
}
