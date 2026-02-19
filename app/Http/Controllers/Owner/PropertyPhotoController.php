<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Properties\ReorderPropertyPhotoRequest;
use App\Http\Requests\Properties\StorePropertyPhotoRequest;
use App\Http\Resources\Properties\PropertyPhotoResource;
use App\Models\Property;
use App\Services\PropertyPhotoService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PropertyPhotoController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/owner/properties/{property}/photos",
     *      summary="Upload photos for a property",
     *      tags={"Owner Property Photos"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="property",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"photos"},
     *                  @OA\Property(
     *                      property="photos",
     *                      type="array",
     *                      @OA\Items(type="string", format="binary")
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Photos uploaded successfully",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/PropertyPhotoResource"))
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      )
     * )
     */
    public function store(Property $property, StorePropertyPhotoRequest $request)
    {
        foreach ($request->file('photos') as $photo) {
            $property->addMedia($photo)->toMediaCollection('photos');
        }

        return response()->json(
            PropertyPhotoResource::collection($property->getMedia('photos')),
            201
        );
    }

    /**
     * @OA\Put(
     *      path="/api/v1/owner/properties/{property}/photos/{photo}/reorder",
     *      summary="Reorder a property photo",
     *      tags={"Owner Property Photos"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="property",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(
     *          name="photo",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"new_order"},
     *              @OA\Property(property="new_order", type="integer", example=1)
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Photo reordered successfully",
     *          @OA\JsonContent(@OA\Property(property="new_order", type="integer"))
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      )
     * )
     */
    public function reorder(Property $property, Media $photo, ReorderPropertyPhotoRequest $request)
    {
        $reordered_photo = PropertyPhotoService::reorder(
            $property,
            $photo,
            $request->new_order
        );

        return response()->json([
            'new_order' => $reordered_photo->order_column
        ], 200);
    }
}
