<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
      use HasFactory;

    protected $table = 'services';

    public $timestamps = false;

    protected $fillable = [
        'service_code',
        'service_name',
        'is_active',
    ];
}
