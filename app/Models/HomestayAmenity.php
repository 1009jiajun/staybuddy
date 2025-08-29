<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomestayAmenity extends Model
{
    protected $table = 'homestay_amenities'; // explicitly set your table name
    protected $primaryKey = 'id';
    protected $fillable = [
        'category',
        'amenity',
        'icon',
    ];

    public $timestamps = false;

    /**
     * Create and return a new amenity.
     *
     * @param  string $category
     * @param  string $amenity
     * @param  string|null $icon
     * @return static
     */
    public static function addAmenity($category, $amenity, $icon = null)
    {
        return self::create([
            'category' => $category,
            'amenity'  => $amenity,
            'icon'     => $icon,
        ]);
    }
}
