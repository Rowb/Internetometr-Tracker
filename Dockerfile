FROM golang:1.25-bookworm AS internetometer-build

WORKDIR /src
RUN git clone --depth 1 --branch 0.1.2 https://github.com/Master290/internetometer-cli.git .
RUN CGO_ENABLED=0 go build -ldflags="-s -w" -o /internetometer ./cmd/cli/main.go

FROM php:7.4-apache-bullseye

RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        cron \
        libzip-dev \
        libsqlite3-dev \
        sqlite3 \
    && docker-php-ext-install pdo_sqlite zip bcmath \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY --from=internetometer-build /internetometer /defaults/www/app/Bin/internetometer
RUN chmod +x /defaults/www/app/Bin/internetometer

WORKDIR /defaults/www

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .
RUN composer dump-autoload --optimize

COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh \
    && mkdir -p /config/www /config/log/speedtest \
    && chown -R www-data:www-data /defaults/www/storage /defaults/www/bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
