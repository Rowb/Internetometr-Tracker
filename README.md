# Speedtest Tracker

[![Docker pulls](https://img.shields.io/docker/pulls/henrywhitaker3/speedtest-tracker?style=flat-square)](https://hub.docker.com/r/henrywhitaker3/speedtest-tracker) [![GitHub Workflow Status](https://img.shields.io/github/workflow/status/henrywhitaker3/Speedtest-Tracker/Stable?label=master&logo=github&style=flat-square)](https://github.com/henrywhitaker3/Speedtest-Tracker/actions) [![GitHub Workflow Status](https://img.shields.io/github/workflow/status/henrywhitaker3/Speedtest-Tracker/Dev?label=dev&logo=github&style=flat-square)](https://github.com/henrywhitaker3/Speedtest-Tracker/actions) [![last_commit](https://img.shields.io/github/last-commit/henrywhitaker3/Speedtest-Tracker?style=flat-square)](https://github.com/henrywhitaker3/Speedtest-Tracker/commits) [![issues](https://img.shields.io/github/issues/henrywhitaker3/Speedtest-Tracker?style=flat-square)](https://github.com/henrywhitaker3/Speedtest-Tracker/issues) [![commit_freq](https://img.shields.io/github/commit-activity/m/henrywhitaker3/Speedtest-Tracker?style=flat-square)](https://github.com/henrywhitaker3/Speedtest-Tracker/commits) ![version](https://img.shields.io/badge/version-v1.12.0-success?style=flat-square) [![license](https://img.shields.io/github/license/henrywhitaker3/Speedtest-Tracker?style=flat-square)](https://github.com/henrywhitaker3/Speedtest-Tracker/blob/master/LICENSE)

This program runs a speedtest check every hour and graphs the results. The back-end is written in [Laravel](https://laravel.com/) and the front-end uses [React](https://reactjs.org/). It uses [internetometer-cli](https://github.com/Master290/internetometer-cli) (Яндекс Интернетометр) to get the data and uses [Chart.js](https://www.chartjs.org/) to plot the results.

A demo can be found [here](https://speedtest.henrywhitaker.com)

![speedtest](https://user-images.githubusercontent.com/36062479/78822484-a82b8300-79ca-11ea-8525-fdeae496a0bd.gif)

## Features

- Automatically run a speedtest every hour
- Graph of previous speedtests going back x days
- Backup/restore data in JSON/CSV format
- Slack/Discord/Telegram notifications
- [healthchecks.io](https://healthchecks.io) integration
- Organizr integration
- InfluxDB integration (currently v1 only, v2 is a WIP)

## Installation & Setup

### Using Docker

Uses [internetometer-cli](https://github.com/Master290/internetometer-cli) (Yandex Internetometer). Requires [Docker Compose](https://docs.docker.com/compose/) v2.

```bash
curl -fsSL https://raw.githubusercontent.com/Rowb/Internetometr-Tracker/master/docker-compose.remote.yml -o docker-compose.yml
DOCKER_BUILDKIT=1 docker compose up -d --build
```

Web UI: `http://localhost:8765` (or `http://<host>:8765`)

If you have cloned this repository:

```bash
docker compose up -d --build
```

#### Parameters

Container images are configured using parameters passed at runtime (such as those above). These parameters are separated by a colon and indicate `<external>:<internal>` respectively. For example, `-p 8080:80` would expose port `80` from inside the container to be accessible from the host's IP on port `8080` outside the container.

|     Parameter             |   Function    |
|     :----:                |   --- |
|     `-p 8765:80`          |   Exposes the webserver on port 8765  |
|     volume `speedtest_config` |   Database, `.env`, logs (mounted at `/config` in the container)   |
|     `-e INTERNETOMETER_LANG` |   Optional. Language for region name in measurements (`ru` or `en`). Defaults to `ru`   |
|     `-e SLACK_WEBHOOK`    |   Optional. Put a slack webhook here to get slack notifications when a speedtest is run. To use discord webhooks, just append `/slack` to the end of your discord webhook URL   |
|     `-e TELEGRAM_BOT_TOKEN`    |   Optional. Telegram bot API token.   |
|     `-e TELEGRAM_CHAT_ID`    |   Optional. Telegram chat ID.   |
|     `-e PUID`             |   Optional. Supply a local user ID for volume permissions   |
|     `-e PGID`             |   Optional. Supply a local group ID for volume permissions  |
|     `-e AUTH`             |   Optional. Set to 'true' to enable authentication for the app |
|     `-e INFLUXDB_RETENTION`|  Optional. Sets the InfluxDB retention period, defaults to `30d` |
|     `-e INFLUXDB_HOST_TAG |   Optional. Sets the InfluxDB host tag value, defaults to `speedtest` |

### Authentication

Authentication is optional. When enabled, unauthenticated users will only be able to see the graphs and tests table. To be able to queue a new speedtest, backup/restore data and update instance settings you will need to log in. To enable authentication, pass the `AUTH=true` environment variable in docker or run `php artisan speedtest:auth --enable` for manual installs (same command with `--disable` to turn it off).

The default credentials are:

|   Field       |   Function        |
|   ---         |   ---             |
|   username    |   admin@admin.com |
|   password    |   password        |
    
After enabling, you should change the password through the web UI.
    
### Manual Install

For manual installations, please follow the instructions [here](https://github.com/henrywhitaker3/Speedtest-Tracker/wiki/Manual-Installation).

Download the [internetometer-cli](https://github.com/Master290/internetometer-cli/releases) binary for your platform, place it at `app/Bin/internetometer`, and make it executable (`chmod +x app/Bin/internetometer`).

### Kubernetes

There is a 3rd party helm chart available [here](https://github.com/sOblivionsCall/charts).
