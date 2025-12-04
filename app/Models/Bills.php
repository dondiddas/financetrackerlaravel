<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bills extends Model
{
    use SoftDeletes;

    protected $table = 'bills';
    protected $fillable = ['user_id','bill_name','amount','due_date','description','is_paid','is_recurring','recurrence_type_id'];

    protected $casts = [
        'due_date' => 'date',
        'is_paid' => 'boolean',
        'is_recurring' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * Recurrence type relation
     */
    public function recurrenceType()
    {
        return $this->belongsTo(RecurrenceType::class, 'recurrence_type_id');
    }
}

