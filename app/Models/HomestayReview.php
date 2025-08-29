<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomestayReview extends Model
{
    public $timestamps = false;

    protected $fillable = ['homestay_id', 'user_name', 'review_text', 'rating', 'review_date'];
    protected $table = 'homestay_reviews'; 

    public function homestay()
    {
        return $this->belongsTo(Homestay::class, 'homestay_id', 'homestay_id');
    }
}