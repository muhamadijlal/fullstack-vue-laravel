<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormBookableAvailabilityRequest;
use App\Models\Bookable;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\Bool_;

class BookableAvailabilityController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(FormBookableAvailabilityRequest $request, Bookable $bookable)
    {
        return $bookable->availableFor($request->from, $request->to)
            ? response()->json([])
            : response()->json([], 404);
    }
}
