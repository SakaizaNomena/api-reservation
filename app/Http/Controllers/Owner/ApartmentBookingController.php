<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Resources\Bookings\BookingResource;
use App\Models\Apartment;
use App\Models\Property;

class ApartmentBookingController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/owner/properties/{property}/apartments/{apartment}/bookings",
     *      summary="Get all bookings for a specific apartment",
     *      tags={"Owner Apartments"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="property",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(
     *          name="apartment",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/BookingResource"))
     *      )
     * )
     */
    public function __invoke(Property $property, Apartment $apartment)
    {
        $bookings = $apartment->bookings()->withTrashed()->get();

        return BookingResource::collection($bookings);
    }
}
