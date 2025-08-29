<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Homestay; // Assuming you have a Homestay model for favourites

class Booking extends Model
{
    protected $fillable = [
        'homestay_id',
        'user_id',
        'check_in_date',
        'check_out_date',
        'total_guests',
        'total_amount', // Total price in RM    
        'external_reference', // Reference for payment
        'toyyibpay_bill_code', // If you store ToyyibPay's bill code
        'transaction_id', // To store ToyyibPay transaction ID
        'paid_at', // Timestamp when payment was made
        'status', // 'unpaid', 'completed', 'cancelled', 'pending'
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
    ];

    public function homestay()
    {
        return $this->belongsTo(Homestay::class, 'homestay_id', 'homestay_id'); // Assuming homestay's PK is 'homestay_id'
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id'); // Assuming user's PK is 'uuid'
    }
    public function homestay_images()
    {
        return $this->hasMany(HomestayImage::class, 'homestay_id', 'homestay_id');
    }
}