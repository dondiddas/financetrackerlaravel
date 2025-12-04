<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecurrenceType extends Model
{
    protected $table = 'recurrence_types';
    protected $fillable = ['name'];

    /**
     * Bills with this recurrence type
     */
    public function bills()
    {
        return $this->hasMany(Bills::class, 'recurrence_type_id');
    }
}
