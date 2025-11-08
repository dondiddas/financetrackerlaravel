<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bills extends Model
{
     protected $table = 'bills'; // Correct property
    protected $fillable = ['userID','title','amount','due_date','is_paid'];

    protected $casts = [
        'due_date' => 'date',
        'is_paid' => 'boolean',
    ];
}
