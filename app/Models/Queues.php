<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queues extends Model
{
    use HasFactory;

    protected $table = 'queues';
    public $timestamps = false;

    protected $fillable = [
        'queue_number',
        'first_name',
        'middle_initial',
        'last_name',
        'contact_number',
        'queue_date',
        'service_id',
        'priority',
        'status',
        'issued_at',
        'called_at',
        'completed_at',
    ];

    protected $casts = [
        'queue_date'   => 'date:Y-m-d',
        'issued_at'    => 'datetime',
        'called_at'    => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($queue) {
            // ✅ Ensure queue_date is set (YYYY-MM-DD)
            if (empty($queue->queue_date)) {
                $queue->queue_date = now()->toDateString(); // e.g., 2026-03-02
            }

            // ✅ Generate queue number: 'CB' + 6 digits (CB000001, CB000002, ...)
            $lastQueue  = self::orderBy('queue_number', 'desc')->first();
            // prefix 'CB' has length 2 → take the numeric part starting at index 2
            $lastNumber = $lastQueue ? (int) substr($lastQueue->queue_number, 2) : 0;
            $queue->queue_number = 'CB' . str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);

            // Optional: set issued_at automatically if not provided
            if (empty($queue->issued_at)) {
                $queue->issued_at = now();
            }

            // Optional: set default status
            if (empty($queue->status)) {
                $queue->status = 'waiting';
            }
        });
    }
}