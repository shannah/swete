#!/bin/bash
set -e

# Generate database config from environment variables
DB_HOST="${DB_HOST:-db}"
DB_NAME="${DB_NAME:-swete}"
DB_USER="${DB_USER:-swete}"
DB_PASSWORD="${DB_PASSWORD:-swete}"

# Cloud SQL Auth Proxy support: when CLOUD_SQL_CONNECTION_NAME is set,
# connect via Unix socket instead of TCP
DB_DRIVER="mysqli"
if [ -n "${CLOUD_SQL_CONNECTION_NAME}" ]; then
    # Cloud SQL proxy provides a Unix socket at this path
    CLOUD_SQL_SOCKET_DIR="${CLOUD_SQL_SOCKET_DIR:-/cloudsql}"
    DB_HOST="${CLOUD_SQL_SOCKET_DIR}/${CLOUD_SQL_CONNECTION_NAME}"
    echo "Using Cloud SQL connection: ${CLOUD_SQL_CONNECTION_NAME}"
fi

cat > /var/www/html/swete-admin/conf.db.ini.php <<CONF
;<?php exit;?>
[_database]
	host="${DB_HOST}"
	name="${DB_NAME}"
	user="${DB_USER}"
	password="${DB_PASSWORD}"
	driver="${DB_DRIVER}"
CONF

# Ensure writable directories exist with correct permissions
mkdir -p /var/www/html/swete-admin/templates_c \
         /var/www/html/swete-admin/livecache \
         /var/www/html/swete-admin/snapshots
chown -R www-data:www-data /var/www/html/swete-admin/templates_c \
                           /var/www/html/swete-admin/livecache \
                           /var/www/html/swete-admin/snapshots

# GCS FUSE: if SWETE_DATA_DIR is set (e.g. mounted GCS bucket), create symlinks
if [ -n "${SWETE_DATA_DIR}" ]; then
    echo "Using external data directory: ${SWETE_DATA_DIR}"
    mkdir -p "${SWETE_DATA_DIR}/livecache" "${SWETE_DATA_DIR}/snapshots" "${SWETE_DATA_DIR}/sites"

    # Symlink livecache and snapshots to the GCS-mounted directory
    if [ ! -L /var/www/html/swete-admin/livecache ]; then
        rm -rf /var/www/html/swete-admin/livecache
        ln -s "${SWETE_DATA_DIR}/livecache" /var/www/html/swete-admin/livecache
    fi
    if [ ! -L /var/www/html/swete-admin/snapshots ]; then
        rm -rf /var/www/html/swete-admin/snapshots
        ln -s "${SWETE_DATA_DIR}/snapshots" /var/www/html/swete-admin/snapshots
    fi
fi

# Wait for database to be ready (skip on Cloud Run where Cloud SQL Proxy handles this)
if [ -z "${CLOUD_SQL_CONNECTION_NAME}" ]; then
    echo "Waiting for database at ${DB_HOST}..."
    until php -r "new mysqli('${DB_HOST}', '${DB_USER}', '${DB_PASSWORD}', '${DB_NAME}');" 2>/dev/null; do
        sleep 2
    done
    echo "Database is ready."
else
    echo "Cloud SQL mode: skipping DB wait (proxy handles connectivity)."
fi

exec "$@"
