<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApartmentPrices\StoreApartmentPriceRequest;
use App\Http\Requests\ApartmentPrices\UpdateApartmentPriceRequest;
use App\Http\Resources\Apartments\ApartmentPriceResource;
use App\Models\Apartment;
use App\Models\ApartmentPrice;
use App\Models\Property;

class ApartmentPriceController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/owner/properties/{property}/apartments/{apartment}/prices",
     *      summary="Get all prices for an apartment",
     *      tags={"Owner Apartment Prices"},
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
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ApartmentPriceResource"))
     *      )
     * )
     */
    public function index(Property $property, Apartment $apartment)
    {
        return ApartmentPriceResource::collection($apartment->prices);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/owner/properties/{property}/apartments/{apartment}/prices",
     *      summary="Create a new price for an apartment",
     *      tags={"Owner Apartment Prices"},
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
     *              required={"start_date", "end_date", "price"},
     *              @OA\Property(property="start_date", type="string", format="date", example="2026-03-01"),
     *              @OA\Property(property="end_date", type="string", format="date", example="2026-03-31"),
     *              @OA\Property(property="price", type="number", format="float", example=100.50)
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Price created",
     *          @OA\JsonContent(@OA\Property(property="message", type="string", example="created."))
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      )
     * )
     */
    public function store(Property $property, Apartment $apartment, StoreApartmentPriceRequest $requset)
    {
        $price = $apartment->prices()->create($requset->validated());

        return response()->json([
            'message' => 'created.'
        ], 201);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/owner/properties/{property}/apartments/{apartment}/prices/{price}",
     *      summary="Get price details",
     *      tags={"Owner Apartment Prices"},
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
     *      @OA\Parameter(
     *          name="price",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ApartmentPriceResource")
     *      )
     * )
     */
    public function show(Property $property, Apartment $apartment, ApartmentPrice $price)
    {
        return ApartmentPriceResource::make($price);
    }

    /**
     * @OA\Put(
     *      path="/api/v1/owner/properties/{property}/apartments/{apartment}/prices/{price}",
     *      summary="Update a price",
     *      tags={"Owner Apartment Prices"},
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
     *      @OA\Parameter(
     *          name="price",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="start_date", type="string", format="date", example="2026-03-01"),
     *              @OA\Property(property="end_date", type="string", format="date", example="2026-03-31"),
     *              @OA\Property(property="price", type="number", format="float", example=120.00)
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Price updated",
     *          @OA\JsonContent(@OA\Property(property="message", type="string", example="updated."))
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      )
     * )
     */
    public function update(Property $property, Apartment $apartment, ApartmentPrice $price, UpdateApartmentPriceRequest $requset)
    {
        $price->update($requset->validated());

        return response()->json([
            'message' => 'updated.'
        ], 200);
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/owner/properties/{property}/apartments/{apartment}/prices/{price}",
     *      summary="Delete a price",
     *      tags={"Owner Apartment Prices"},
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
     *      @OA\Parameter(
     *          name="price",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Price deleted"
     *      )
     * )
     */
    public function destroy(Property $property, Apartment $apartment, ApartmentPrice $price)
    {
        $price->delete();

        return response()->noContent();
    }
}
