<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serving extends Model
{
    use HasFactory;
     protected $table = 'now_queue_serving';

    public $timestamps = false;

    protected $fillable = [
        'queue_number',
        'first_name',
        'middle_initial',
        'last_name',
        'contact_number',
        'queue_date',
        'service_name',
        'priority',
        'status',
        'issued_at',
        'called_at',
        'completed_at',
    ];
}
