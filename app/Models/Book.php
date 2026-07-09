<?php

namespace App\Models;

use App\Models\Concerns\UsesSqlServerSchemaTable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory, UsesSqlServerSchemaTable;
    protected $fillable = [
        'title',
        'author',
        'isbn',
        'available_copies',
        'category',
        'cover_path',
        'description',
    ];

    public function loans(){
        return $this->hasMany(Loan::class);
    }
}
