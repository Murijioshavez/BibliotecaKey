<?php

namespace App\Models\Concerns;

use App\Support\DatabaseTable;

trait UsesSqlServerSchemaTable
{
    public function getTable()
    {
        return DatabaseTable::name(parent::getTable());
    }
}
