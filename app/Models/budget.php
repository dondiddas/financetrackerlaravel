<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use SoftDeletes;

    protected $table = 'budgets';

    protected $fillable = [
        'user_id', 'category_id', 'amount', 'note'
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }
}



