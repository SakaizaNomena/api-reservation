<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apartment\StoreApartmentRequest;
use App\Http\Requests\Apartment\UpdateApartmentRequest;
use App\Http\Resources\Apartments\ApartmentDetailsResource;
use App\Models\Apartment;
use App\Models\Property;
use App\Services\ApartmentService;

class ApartmentController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/owner/properties/{property}/apartments",
     *      summary="Get all apartments for a property",
     *      tags={"Owner Apartments"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="property",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ApartmentDetailsResource"))
     *      )
     * )
     */
    public function index(Property $property)
    {
        $apartments = $property->apartments()->withoutGlobalScopes()
            ->with(
                'apartment_type',
                'facilities',
                'prices',
                'bookings',
                'beds.bed_type'
            )->get();

        return ApartmentDetailsResource::collection($apartments);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/owner/properties/{property}/apartments",
     *      summary="Create a new apartment",
     *      tags={"Owner Apartments"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="property",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "apartment_type_id", "size"},
     *              @OA\Property(property="name", type="string", example="Apartment 101"),
     *              @OA\Property(property="apartment_type_id", type="integer", example=1),
     *              @OA\Property(property="size", type="integer", example=50)
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Apartment created",
     *          @OA\JsonContent(@OA\Property(property="message", type="string"))
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      )
     * )
     */
    public function store(Property $property, StoreApartmentRequest $request)
    {
        $message = ApartmentService::create($property, $request);

        return response()->json([
            'message' => $message
        ], 201);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/owner/properties/{property}/apartments/{apartment}",
     *      summary="Get apartment details",
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
     *          @OA\JsonContent(ref="#/components/schemas/ApartmentDetailsResource")
     *      )
     * )
     */
    public function show(Property $property, Apartment $apartment)
    {
        $apartment->load(
            'apartment_type',
            'facilities',
            'prices',
            'bookings',
            'beds.bed_type'
        );

        return ApartmentDetailsResource::make($apartment);
    }

    /**
     * @OA\Put(
     *      path="/api/v1/owner/properties/{property}/apartments/{apartment}",
     *      summary="Update an apartment",
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
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="Updated Apartment"),
     *              @OA\Property(property="size", type="integer", example=60)
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Apartment updated",
     *          @OA\JsonContent(@OA\Property(property="message", type="string", example="updated."))
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      )
     * )
     */
    public function update(Property $property, Apartment $apartment, UpdateApartmentRequest $request)
    {
        $apartment->update($request->validated());

        return response()->json([
            'message' => 'updated.'
        ], 200);
    }

    /**
     * @OA\Put(
     *      path="/api/v1/owner/properties/{property}/apartments/{apartment}/deactivate",
     *      summary="Deactivate an apartment",
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
     *          description="Apartment deactivated",
     *          @OA\JsonContent(@OA\Property(property="message", type="string", example="deactivated."))
     *      )
     * )
     */
    public function deactivate(Property $property, Apartment $apartment)
    {
        $apartment->deactivate();

        return response()->json([
            'message' => 'deactivated.'
        ], 200);
    }

    /**
     * @OA\Put(
     *      path="/api/v1/owner/properties/{property}/apartments/{apartment}/activate",
     *      summary="Activate an apartment",
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
     *          description="Apartment activated",
     *          @OA\JsonContent(@OA\Property(property="message", type="string", example="activated."))
     *      )
     * )
     */
    public function activate(Property $property, Apartment $apartment)
    {
        $apartment->activate();

        return response()->json([
            'message' => 'activated.'
        ], 200);
    }
}
