<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavouriteHomestay extends Model
{
    use HasFactory;

    // By default, Laravel assumes primary key 'id'.
    // Since we have a composite primary key ('user_id', 'homestay_id'),
    // we need to set $incrementing to false and $primaryKey to an array.
    // However, for pivot tables like this, it's often better not to define a $primaryKey
    // or rely on Laravel's Eloquent to handle the composite key for you.
    // If you strictly need it, you can define it, but for 'belongsToMany' it's usually handled.
    // For this specific use case (retrieving via User hasMany Favourites),
    // we don't need to explicitly define it.
        // Add this line to disable automatic timestamps
    public $timestamps = false;

    protected $table = 'favourite_homestays'; // Explicitly specify table name

    // If you were using this model for direct creation, you'd list fillable.
    // For pivot table, often handled through parent models' relationships.
    protected $fillable = [
        'user_id',
        'homestay_id',
        'added_at',
    ];

    protected $casts = [
        'added_at' => 'datetime',
    ];

    // Define relationship to User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid'); // Assuming User model uses 'uuid' as PK
    }

    // Define relationship to Homestay
    public function homestay()
    {
        return $this->belongsTo(Homestay::class, 'homestay_id', 'uuid'); // Assuming Homestay model uses 'uuid' as PK
    }
}