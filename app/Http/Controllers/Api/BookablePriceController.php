<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormBookablePriceRequest;
use App\Models\Bookable;

class BookablePriceController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(FormBookablePriceRequest $request,Bookable $bookable)
    {
        return response()->json([
            'data' => $bookable->pricefor($request->from, $request->to)
        ]);
    }
}
