<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'password',
        'profile_image'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';

     // Define the many-to-many relationship with Homestay through favourite_homestays table
    public function favouriteHomestays()
    {
        return $this->belongsToMany(Homestay::class, 'favourite_homestays', 'user_id', 'homestay_id')
                    ->withPivot('added_at'); // To access the 'added_at' column
    }

    // You can also define a hasMany relationship to the pivot model itself if you need to access pivot data directly
    public function favourites()
    {
        return $this->hasMany(FavouriteHomestay::class, 'user_id', 'user_id'); // Assuming user's PK is 'uuid'
    }
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favourite_homestays', 'homestay_id', 'user_id');
    }

    protected static function boot()
{
    parent::boot();

    static::creating(function ($user) {
        $user->user_id = \Str::uuid();
    });
}


}
