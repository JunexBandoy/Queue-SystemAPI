<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waiting extends Model
{
    use HasFactory;
     protected $table = 'queue_waiting';

    public $timestamps = false;

    protected $fillable = [
        'queue_number',
        'queue_date',
        'service_name',
        'priority',
        'status',
        'issued_at',
        'called_at',
        'completed_at',
    ];
}
