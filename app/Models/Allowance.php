<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allowance extends Model
{
    protected $table = 'allowances';

    protected $primaryKey = 'id';

    protected $fillable = [
        'userID',
        'amount',
        'month_year',
    ];
}
