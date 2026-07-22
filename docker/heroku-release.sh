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

php artisan config:clear

php -r 'require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$connection = config("database.default");
$prefix = config("database.connections.$connection.prefix", "");
$schema = config("database.connections.sqlsrv.schema");
$migrationTable = config("database.migrations.table");
echo "Database connection: {$connection}\n";
echo "SQL Server schema: {$schema}\n";
echo "Table prefix: ".($prefix === "" ? "<empty>" : $prefix)."\n";
echo "Migration table: {$migrationTable}\n";
if ($prefix !== "") {
    fwrite(STDERR, "The SQL Server table prefix must be empty. Use DB_SCHEMA for biblioteca, not a table prefix.\n");
    exit(1);
}'

php artisan migrate --force
