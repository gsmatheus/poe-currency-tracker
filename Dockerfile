FROM php:8.3-fpm

RUN apt-get update && apt-get install -y git unzip libsqlite3-dev cron \
    && docker-php-ext-install pdo pdo_sqlite

RUN sed -i 's/listen = .*/listen = 0.0.0.0:9000/' /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www/html
RUN git config --global --add safe.directory /var/www/html

# Copy everything first
COPY . .

# Composer install (after all files are copied)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --prefer-dist --optimize-autoloader

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh 

# Make scripts executable
RUN chmod +x /usr/local/bin/docker-entrypoint.sh \
    && chmod +x bin/fetch-currency.sh

    

# Log file for cron
RUN touch /var/www/html/fetch-currency.log \
    && chmod 666 /var/www/html/fetch-currency.log

COPY crontab /etc/cron.d/items-cron
RUN chmod 644 /etc/cron.d/items-cron

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
