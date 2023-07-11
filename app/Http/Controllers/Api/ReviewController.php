<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function show(Review $review)
    {
        return new ReviewResource($review);
    }

    public function store(FormReviewRequest $request)
    {
        $booking = Booking::findByReviewKey($request['id']);
        // dd($booking);

        if(null === $booking)
        {
            return abort(404);
        }
        
        $booking->review_key = '';
        $booking->save();
        
        $review = Review::make($request->all());
        $review->bookable_id = $booking->bookable_id;
        $review->booking_id = $booking->id;

        $review->save();

        return new ReviewResource($review);
    }
}