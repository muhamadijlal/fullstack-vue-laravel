<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['id','bookable_id','from','to'];

    public function bookable(): BelongsTo
    {
        return $this->belongsTo(Bookable::class, 'bookable_id');
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class, 'booking_id');
    }

    public function scopeBetweenDates(Builder $query, $from, $to)
    {
        return $query->where('to', '>=', $from)->where('from','<=', $to);
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }

    public static function findByReviewKey(string $review_key): ?Booking
    {
        return static::where('review_key', $review_key)->with('bookable')->get()->first();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($booking){
            $booking->review_key = Str::uuid();
        });
    }
}
