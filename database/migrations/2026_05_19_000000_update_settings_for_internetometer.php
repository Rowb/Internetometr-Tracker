<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

class UpdateSettingsForInternetometer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Setting::where('name', 'server')->update([
            'description' => '<p class="d-inline text-muted">Не используется: измерения выполняются через <a href="https://yandex.ru/internet" target="_blank" rel="noopener noreferrer">Яндекс Интернетометр</a>.</p>',
        ]);

        Setting::where('name', 'speedtest_provider')->update([
            'value' => 'internetometer',
            'description' => 'Провайдер измерений скорости (Яндекс Интернетометр).',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Setting::where('name', 'server')->update([
            'description' => '<p class="d-inline">Comma-separated list of speedtest.net server IDs picked randomly. Leave blank to use default settings.</p>',
        ]);

        Setting::where('name', 'speedtest_provider')->update([
            'value' => 'ookla',
            'description' => 'The provider/package used to run speedtests.',
        ]);
    }
}
