<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Properties\StorePropertyRequest;
use App\Http\Requests\Properties\UpdatePropertyRequest;
use App\Http\Resources\Properties\PropertyDetailsResource;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/owner/properties",
     *      summary="Get a list of properties for the authenticated owner",
     *      tags={"Owner Properties"},
     *      security={{"sanctum":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/PropertyDetailsResource"))
     *      )
     * )
     */
    public function index(Request $request)
    {
        $properties = $request->user()->properties()->withoutGlobalScopes()
            ->with(
                'city:id,name',
                'facilities',
                'bookings'
            )->get();

        return PropertyDetailsResource::collection($properties);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/owner/properties",
     *      summary="Create a new property",
     *      tags={"Owner Properties"},
     *      security={{"sanctum":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "city_id", "address_street", "address_postcode"},
     *              @OA\Property(property="name", type="string", example="Beautiful Villa"),
     *              @OA\Property(property="city_id", type="integer", example=1),
     *              @OA\Property(property="address_street", type="string", example="123 Main St"),
     *              @OA\Property(property="address_postcode", type="string", example="12345")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Property created",
     *          @OA\JsonContent(@OA\Property(property="message", type="string", example="created."))
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      )
     * )
     */
    public function store(StorePropertyRequest $request)
    {
        Property::create($request->validated());

        return response()->json([
            'message' => 'created.'
        ], 201);
    }

    /**
     * @OA\Put(
     *      path="/api/v1/owner/properties/{property}",
     *      summary="Update a property",
     *      tags={"Owner Properties"},
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
     *              @OA\Property(property="name", type="string", example="Updated Villa Name"),
     *              @OA\Property(property="active", type="boolean", example=true)
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Property updated",
     *          @OA\JsonContent(@OA\Property(property="message", type="string", example="updated."))
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      )
     * )
     */
    public function update(Property $property, UpdatePropertyRequest $request)
    {
        $property->update($request->validated());

        return response()->json([
            'message' => 'updated.'
        ], 200);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/owner/properties/{property}",
     *      summary="Get property details",
     *      tags={"Owner Properties"},
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
     *          @OA\JsonContent(ref="#/components/schemas/PropertyDetailsResource")
     *      )
     * )
     */
    public function show(Property $property)
    {
        return PropertyDetailsResource::make($property);
    }

    /**
     * @OA\Put(
     *      path="/api/v1/owner/properties/{property}/deactivate",
     *      summary="Deactivate a property",
     *      tags={"Owner Properties"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="property",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Property deactivated",
     *          @OA\JsonContent(@OA\Property(property="message", type="string", example="deactivated."))
     *      )
     * )
     */
    public function deactivate(Property $property)
    {
        $property->deactivate();

        return response()->json([
            'message' => 'deactivated.'
        ], 200);
    }

    /**
     * @OA\Put(
     *      path="/api/v1/owner/properties/{property}/activate",
     *      summary="Activate a property",
     *      tags={"Owner Properties"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="property",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Property activated",
     *          @OA\JsonContent(@OA\Property(property="message", type="string", example="activated."))
     *      )
     * )
     */
    public function activate(Property $property)
    {
        $property->activate();

        return response()->json([
            'message' => 'activated.'
        ], 200);
    }
}
