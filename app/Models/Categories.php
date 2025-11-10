<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $fillable = [
        'name',
        'type',
        'user_id', // add this so you can mass assign user_id
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

