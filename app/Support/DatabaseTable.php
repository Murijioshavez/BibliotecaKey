<?php

namespace App\Support;

class DatabaseTable
{
    public static function name(string $table): string
    {
        if (str_contains($table, '.')) {
            return $table;
        }

        if (config('database.default') !== 'sqlsrv') {
            return $table;
        }

        $schema = config('database.connections.sqlsrv.schema');

        return $schema ? "{$schema}.{$table}" : $table;
    }

    public static function validationName(string $table): string
    {
        $connection = config('database.default');

        if ($connection !== 'sqlsrv') {
            return $table;
        }

        if (str_contains($table, '.')) {
            return str_starts_with($table, "{$connection}.") ? $table : "{$connection}.{$table}";
        }

        $schema = config('database.connections.sqlsrv.schema');

        return $schema ? "{$connection}.{$schema}.{$table}" : $table;
    }
}
