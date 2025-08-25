#!/bin/bash
set -e

# Ensure cron runs in the project directory
cd /var/www/html

# Call PHP script using absolute path to PHP binary
/usr/local/bin/php bin/fetch-currency.php "Mercenaries"

echo "Fetch done at $(date)"
