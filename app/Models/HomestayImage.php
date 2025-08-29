<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomestayImage extends Model
{
    public $timestamps = false;

    protected $fillable = ['homestay_id', 'image_url'];
    protected $table = 'homestay_images'; 

    public function homestay()
    {
        return $this->belongsTo(Homestay::class, 'homestay_id', 'homestay_id');
    }
}