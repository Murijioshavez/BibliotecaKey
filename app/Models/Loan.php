<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'book_id',
        'status',
        'fecha_prestamo',
        'fecha_vencimiento',
        'returned_at'
    ];
        protected $casts = [
        'fecha_prestamo' => 'datetime',
        'fecha_vencimiento' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
