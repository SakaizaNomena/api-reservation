<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bookings\StoreBookingRequest;
use App\Http\Requests\Bookings\UpdateBookingRequest;
use App\Http\Resources\Bookings\BookingResource;
use App\Jobs\UpdatePropertyRatingJob;
use App\Models\Booking;

class BookingController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/user/bookings",
     *      summary="Get all user bookings",
     *      tags={"User Bookings"},
     *      security={{"sanctum":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/BookingResource"))
     *      )
     * )
     */
    public function index()
    {
        $bookings = auth()->user()->bookings()
            ->with('apartment.property')
            ->withTrashed()
            ->orderBy('start_date')
            ->get();

        return BookingResource::collection($bookings);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/user/bookings",
     *      summary="Create a new booking",
     *      tags={"User Bookings"},
     *      security={{"sanctum":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"apartment_id", "start_date", "end_date", "guests_adults"},
     *              @OA\Property(property="apartment_id", type="integer", example=1),
     *              @OA\Property(property="start_date", type="string", format="date", example="2026-03-15"),
     *              @OA\Property(property="end_date", type="string", format="date", example="2026-03-20"),
     *              @OA\Property(property="guests_adults", type="integer", example=2),
     *              @OA\Property(property="guests_children", type="integer", example=1)
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Booking created successfully",
     *          @OA\JsonContent(ref="#/components/schemas/BookingResource")
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      )
     * )
     */
    public function store(StoreBookingRequest $request)
    {
        $booking = auth()->user()->bookings()->create($request->validated());

        return BookingResource::make($booking);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/user/bookings/{booking}",
     *      summary="Get booking details",
     *      tags={"User Bookings"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="booking",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/BookingResource")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Booking not found"
     *      )
     * )
     */
    public function show(Booking $booking)
    {
        return BookingResource::make($booking);
    }

    /**
     * @OA\Put(
     *      path="/api/v1/user/bookings/{booking}",
     *      summary="Update a booking",
     *      tags={"User Bookings"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="booking",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="start_date", type="string", format="date", example="2026-03-16"),
     *              @OA\Property(property="end_date", type="string", format="date", example="2026-03-21"),
     *              @OA\Property(property="guests_adults", type="integer", example=2),
     *              @OA\Property(property="rating", type="integer", minimum=1, maximum=10, example=9)
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Booking updated",
     *          @OA\JsonContent(@OA\Property(property="message", type="string", example="updated."))
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      )
     * )
     */
    public function update(Booking $booking, UpdateBookingRequest $request)
    {
        $booking->update($request->validated());

        dispatch(new UpdatePropertyRatingJob($booking));

        return response()->json([
            'message' => 'updated.'
        ], 200);
    }

    /**
     * @OA\Put(
     *      path="/api/v1/user/bookings/{booking}/cancel",
     *      summary="Cancel a booking",
     *      tags={"User Bookings"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="booking",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Booking cancelled",
     *          @OA\JsonContent(@OA\Property(property="message", type="string", example="cancelled."))
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Booking not found"
     *      )
     * )
     */
    public function cancel(Booking $booking)
    {
        $booking->cancel();

        return response()->json([
            'message' => 'cancelled.'
        ], 200);
    }
}
