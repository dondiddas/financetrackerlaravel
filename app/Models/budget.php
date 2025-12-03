<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $table = 'budgets';

    protected $fillable = [
        'user_id', 'category_id', 'amount', 'note'
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
}



