<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyLimit extends Model
{
    use HasFactory;

    protected $table = 'daily_limits';

    protected $fillable = [
        'user_id',
        'expense_limit',
        'limit_date',
    ];

    protected $casts = [
        'limit_date' => 'date',
        'expense_limit' => 'decimal:2',
    ];
}
