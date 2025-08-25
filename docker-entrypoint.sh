#!/bin/bash
set -e


chmod +x bin/fetch-currency.sh
# Composer scripts
composer run-script post-install-cmd || true

touch /var/www/html/data/poe_tracker.sqlite
chmod 777 /var/www/html/data/poe_tracker.sqlite


# Database update
echo "Running Doctrine schema update..."
vendor/bin/doctrine-module orm:schema-tool:update --force --complete

# Import items and fetch currency
echo "Importing items.json into database..."
php bin/fetch-currency-item.php

echo "Fetching latest currency rates..."
bin/fetch-currency.sh

# Start cron
echo "Starting cron..."
cron -f &

# Start PHP-FPM in foreground
echo "Starting PHP-FPM..."
php-fpm -F
