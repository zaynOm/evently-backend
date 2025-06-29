# # syntax = docker/dockerfile:experimental
#
# ARG PHP_VERSION=8.4
# ARG NODE_VERSION=18
# FROM ubuntu:22.04 as base
# LABEL fly_launch_runtime="laravel"
#
# # PHP_VERSION needs to be repeated here
# # See https://docs.docker.com/engine/reference/builder/#understand-how-arg-and-from-interact
# ARG PHP_VERSION
# ENV DEBIAN_FRONTEND=noninteractive \
#     COMPOSER_ALLOW_SUPERUSER=1 \
#     COMPOSER_HOME=/composer \
#     COMPOSER_MAX_PARALLEL_HTTP=24 \
#     PHP_PM_MAX_CHILDREN=10 \
#     PHP_PM_START_SERVERS=3 \
#     PHP_MIN_SPARE_SERVERS=2 \
#     PHP_MAX_SPARE_SERVERS=4 \
#     PHP_DATE_TIMEZONE=UTC \
#     PHP_DISPLAY_ERRORS=Off \
#     PHP_ERROR_REPORTING=22527 \
#     PHP_MEMORY_LIMIT=256M \
#     PHP_MAX_EXECUTION_TIME=90 \
#     PHP_POST_MAX_SIZE=100M \
#     PHP_UPLOAD_MAX_FILE_SIZE=100M \
#     PHP_ALLOW_URL_FOPEN=Off
#
# # Prepare base container:
# # 1. Install PHP, Composer
# COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
# COPY .fly/php/ondrej_ubuntu_php.gpg /etc/apt/trusted.gpg.d/ondrej_ubuntu_php.gpg
# ADD .fly/php/packages/${PHP_VERSION}.txt /tmp/php-packages.txt
#
# RUN apt-get update \
#     && apt-get install -y --no-install-recommends gnupg2 ca-certificates git-core curl zip unzip \
#                                                   rsync vim-tiny htop sqlite3 nginx supervisor cron \
#     && ln -sf /usr/bin/vim.tiny /etc/alternatives/vim \
#     && ln -sf /etc/alternatives/vim /usr/bin/vim \
#     && echo "deb http://ppa.launchpad.net/ondrej/php/ubuntu jammy main" > /etc/apt/sources.list.d/ondrej-ubuntu-php-focal.list \
#     && apt-get update \
#     && apt-get -y --no-install-recommends install $(cat /tmp/php-packages.txt) \
#     && ln -sf /usr/sbin/php-fpm${PHP_VERSION} /usr/sbin/php-fpm \
#     && mkdir -p /var/www/html/public && echo "index" > /var/www/html/public/index.php \
#     && apt-get clean \
#     && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
#
# # # 2. Copy config files to proper locations
# COPY .fly/nginx/ /etc/nginx/
# COPY .fly/fpm/ /etc/php/${PHP_VERSION}/fpm/
# COPY .fly/supervisor/ /etc/supervisor/
# COPY .fly/entrypoint.sh /entrypoint
# COPY .fly/start-nginx.sh /usr/local/bin/start-nginx
# RUN chmod 754 /usr/local/bin/start-nginx
#
# # # 3. Copy application code, skipping files based on .dockerignore
# COPY . /var/www/html
# WORKDIR /var/www/html
#
# # # 4. Setup application dependencies
# RUN composer install --optimize-autoloader --no-dev --no-scripts \
#     && mkdir -p storage/logs \
#     && php artisan optimize:clear \
#     && chown -R www-data:www-data /var/www/html \
#     && echo "MAILTO=\"\"\n* * * * * www-data /usr/bin/php /var/www/html/artisan schedule:run" > /etc/cron.d/laravel \
#     && sed -i='' '/->withMiddleware(function (Middleware \$middleware) {/a\
#         \$middleware->trustProxies(at: "*");\
#     ' bootstrap/app.php; \
#     if [ -d .fly ]; then cp .fly/entrypoint.sh /entrypoint; chmod +x /entrypoint; fi;
#
# # Create storage and cache directories with correct permissions
# RUN mkdir -p /var/www/html/storage/framework/{sessions,views,cache} \
#     && mkdir -p /var/www/html/bootstrap/cache \
#     && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
#     && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
#
# # 5. Setup Entrypoint
# EXPOSE 8080
#
# ENTRYPOINT ["/entrypoint"]

FROM php:8.2-fpm

# System dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# App code
WORKDIR /var/www
COPY . .

RUN composer install --optimize-autoloader --no-dev
RUN php artisan config:cache

CMD php artisan serve --host=0.0.0.0 --port=10000
