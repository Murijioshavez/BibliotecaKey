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
}
