<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bills extends Model
{
    protected $table = 'bills'; 
    protected $fillable = ['user_id','bill_name','amount','due_date','description','is_paid','is_recurring','recurrence_interval'];

    protected $casts = [
        'due_date' => 'date',
        'is_paid' => 'boolean',
        'is_recurring' => 'boolean',
    ];
}

