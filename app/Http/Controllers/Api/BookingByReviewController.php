<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingByReviewShowResource;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingByReviewController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $review_key)
    {

        $booking = Booking::findByReviewKey($review_key);

        return $booking ? new BookingByReviewShowResource($booking) : abort(404);
    }
}
