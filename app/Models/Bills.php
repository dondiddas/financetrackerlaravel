<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bills extends Model
{
    protected $table = 'bills'; 
    protected $fillable = ['user_id','bill_name','amount','due_date','description','is_paid'];

    protected $casts = [
        'due_date' => 'date',
        'is_paid' => 'boolean',
    ];
}

