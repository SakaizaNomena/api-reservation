<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Properties\PropertyViewResource;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyViewController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/properties/view/{property}",
     *      summary="View public property details",
     *      tags={"Public"},
     *      @OA\Parameter(
     *          name="property",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/PropertyViewResource")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Property not found"
     *      )
     * )
     */
    public function __invoke(Property $property)
    {
        $property->load(['apartments' => function ($query) {
            $query->whereHas('prices');
        }]);

        return PropertyViewResource::make($property);
    }
}
