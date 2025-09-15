<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'category_id',
        'name',
        'description',
        'location',
        'event_date',
        'registration_deadline',
        'price',
        'capacity',
        'available_slots',
        'images',
        'discount_type',
        'discount_value',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
        'event_date' => 'datetime',
        'registration_deadline' => 'datetime',
    ];

    public function provider() {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function bookings() {
        return $this->morphMany(Booking::class, 'bookable');
    }

    public function ratings() {
        return $this->morphMany(Rating::class, 'rateable');
    }

    public function bookmarks() {
        return $this->morphMany(Bookmark::class, 'bookmarkable');
    }
}
