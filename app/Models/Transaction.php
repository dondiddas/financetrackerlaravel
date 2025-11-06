<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'userID',
        'category_id',
        'amount',
        'note',
        'transaction_date',
    ];

    protected $dates = ['transaction_date'];
}
