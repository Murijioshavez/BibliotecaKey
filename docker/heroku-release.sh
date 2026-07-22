#!/bin/sh
set -eu

if [ "${DB_CONNECTION:-}" != "sqlsrv" ]; then
    echo "DB_CONNECTION must be set to sqlsrv before running Heroku release migrations."
    exit 1
fi

if [ -z "${DB_SCHEMA:-}" ]; then
    echo "DB_SCHEMA must be set before running Heroku release migrations."
    exit 1
fi

php artisan migrate --force
