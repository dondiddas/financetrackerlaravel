<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'amount',
        'note',
        'transaction_date',
        'user_id',       // allow mass assignment
        'category_id',   // allow mass assignment
    ];

    protected $dates = ['transaction_date'];

    // Link to category
    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    // Link to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
