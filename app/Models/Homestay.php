<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Homestay extends Model
{
    public $incrementing = false; // because you're using UUID
    protected $keyType = 'string';
    // Explicitly define the table name
    protected $table = 'homestay'; 
    protected $primaryKey = 'homestay_id'; // explicitly set your PK
    protected $appends = ['amenities_list'];
    // protected $hidden = ['amenity_ids'];
    protected $casts = [
        'amenity_ids' => 'array',
    ];

    protected $fillable = [
        'homestay_id', 'title', 'description', 'host_id', 'location_country',
        'location_state', 'location_city', 'address', 'price_per_night',
        'currency', 'cleaning_fee', 'room_type', 'bedrooms', 'beds', 'bathrooms',
        'max_guests', 'rating_avg', 'reviews_count', 'cancellation_policy',
        'check_in_time', 'check_out_time', 'is_guest_favorite', 'tags', 'amenity_ids', 'rules',
        'latitude', 'longitude', 'created_at', 'updated_at'
    ];

    public function images()
    {
        return $this->hasMany(HomestayImage::class, 'homestay_id', 'homestay_id');
    }

    public function reviews()
    {
        return $this->hasMany(HomestayReview::class, 'homestay_id', 'homestay_id');
    }

     public function bookings()
    {
        return $this->hasMany(Booking::class, 'homestay_id', 'homestay_id');
    }

    public function getAmenitiesListAttribute()
    {
        return HomestayAmenity::whereIn('id', $this->amenity_ids ?: [])->get();
    }
}