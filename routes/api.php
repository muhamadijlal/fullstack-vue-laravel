<?php

use App\Http\Controllers\Api\BookableAvailabilityController;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookableController;
use App\Http\Controllers\Api\BookablePriceController;
use App\Http\Controllers\Api\BookableReviewController;
use App\Http\Controllers\Api\BookingByReviewController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Auth\Events\Registered;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::apiResource('bookables', BookableController::class)->only(['index','show']);
Route::get('bookables/{bookable}/availability', BookableAvailabilityController::class)->name('bookables.availabilty.show');
Route::get('bookables/{bookable}/reviews', BookableReviewController::class)->name('bookables.reviews.index');
Route::get('bookables/{bookable}/price', BookablePriceController::class)->name('bookables.price.show');
Route::get('/booking-by-review/{reviewKey}', BookingByReviewController::class)->name('booking.by-review.show');
Route::apiResource('reviews', ReviewController::class)->only(['show','store']);
Route::post('checkout', CheckoutController::class)->name('checkout');