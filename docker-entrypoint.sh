#!/bin/bash
set -e

# Generate database config from environment variables
DB_HOST="${DB_HOST:-db}"
DB_NAME="${DB_NAME:-swete}"
DB_USER="${DB_USER:-swete}"
DB_PASSWORD="${DB_PASSWORD:-swete}"

cat > /var/www/html/swete-admin/conf.db.ini.php <<CONF
;<?php exit;?>
[_database]
	host="${DB_HOST}"
	name="${DB_NAME}"
	user="${DB_USER}"
	password="${DB_PASSWORD}"
	driver="mysqli"
CONF

# Ensure writable directories exist with correct permissions
mkdir -p /var/www/html/swete-admin/templates_c \
         /var/www/html/swete-admin/livecache \
         /var/www/html/swete-admin/snapshots
chown -R www-data:www-data /var/www/html/swete-admin/templates_c \
                           /var/www/html/swete-admin/livecache \
                           /var/www/html/swete-admin/snapshots

# Wait for database to be ready
echo "Waiting for database at ${DB_HOST}..."
until php -r "new mysqli('${DB_HOST}', '${DB_USER}', '${DB_PASSWORD}', '${DB_NAME}');" 2>/dev/null; do
    sleep 2
done
echo "Database is ready."

exec "$@"
