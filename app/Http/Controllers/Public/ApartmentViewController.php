<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApartmentDetailsResource;
use App\Http\Resources\Apartments\ApartmentViewResource;
use App\Models\Apartment;

class ApartmentViewController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/apartments/view/{apartment}",
     *      summary="View public apartment details",
     *      tags={"Public"},
     *      @OA\Parameter(
     *          name="apartment",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ApartmentViewResource")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Apartment not found"
     *      )
     * )
     */
    public function __invoke(Apartment $apartment)
    {
        $apartment->load(
            'facilities.category',
            'prices',
            'bookings:id,apartment_id,start_date,end_date'
        );

        $apartment->setAttribute(
            'facility_categories',
            $apartment->facilities
                ->groupBy('category.name')
                ->mapWithKeys(fn ($items, $key) => [$key => $items->pluck('name')])
        );

        return ApartmentViewResource::make($apartment);
    }
}
