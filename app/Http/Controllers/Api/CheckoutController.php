<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormCheckoutRequest;
use App\Models\Address;
use App\Models\Bookable;
use App\Models\Booking;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(FormCheckoutRequest $request)
    {
        $request->validate([
            'bookings.*' => ['required', function($attribute, $value, $fail) {
                $bookable = Bookable::findOrFail($value['bookable_id']);
    
                if(!$bookable->availableFor($value['from'], $value['to'])) {
                    $fail("The object is not available in given dates!");
                }
            }]
        ]);

        $bookingsData = $request['bookings'];
        $addressData = $request['customer'];
        $addressData["id"] = Str::uuid()->toString(); // assign id into address data

        $bookings = collect($bookingsData)->map(function ($bookingData) use($addressData) {
            $bookable = Bookable::findOrFail($bookingData['bookable_id']);
            $booking = new Booking();

            
            $booking->id = Str::uuid()->toString();
            $booking->address()->associate(Address::create($addressData)); // create id relation
            $booking->from = $bookingData['from'];
            $booking->to = $bookingData['to'];
            $booking->price = $bookable->pricefor($booking->from, $booking->to)['total'];
            $booking->bookable()->associate($bookable);

            $booking->save();

            return $booking;
        });

        return $bookings;
    }
}