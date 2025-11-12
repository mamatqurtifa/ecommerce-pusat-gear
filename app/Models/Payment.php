<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_reference',
        'duitku_reference',
        'payment_method',
        'amount',
        'status',
        'duitku_response',
        'paid_at',
        'expired_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'duitku_response' => 'array',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime'
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Menunggu Pembayaran',
            'success' => 'Berhasil',
            'failed' => 'Gagal',
            'expired' => 'Kadaluarsa',
            'cancelled' => 'Dibatalkan',
            default => 'Unknown'
        };
    }

    public function getIsExpiredAttribute()
    {
        return $this->expired_at && now()->isAfter($this->expired_at);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    // Methods
    public function markAsSuccess()
    {
        $this->update([
            'status' => 'success',
            'paid_at' => now()
        ]);

        $this->order->markAsPaid();
    }

    public function markAsFailed()
    {
        $this->update(['status' => 'failed']);
    }
}