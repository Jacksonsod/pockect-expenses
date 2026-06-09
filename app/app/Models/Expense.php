<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'amount',
        'category',
        'description',
    ];

    protected $casts = [
        'amount' => 'integer',
    ];
}
