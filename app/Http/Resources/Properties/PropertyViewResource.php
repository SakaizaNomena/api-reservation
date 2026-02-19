<?php

namespace App\Http\Resources\Properties;

use App\Http\Resources\Apartments\ApartmentViewResource;
use App\Http\Resources\Facilities\FacilityResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="PropertyViewResource",
 *     type="object",
 *     @OA\Property(property="name", type="string", example="Beautiful Villa"),
 *     @OA\Property(property="address", type="string", example="123 Main St, 12345"),
 *     @OA\Property(property="facilities", type="array", @OA\Items(type="object")),
 *     @OA\Property(property="apartments", type="array", @OA\Items(ref="#/components/schemas/ApartmentViewResource")),
 *     @OA\Property(property="photos", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="avg_rating", type="number", format="float", nullable=true, example=8.5)
 * )
 */
class PropertyViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'address' => $this->address,
            'facilities' => FacilityResource::collection($this->whenLoaded('facilities')),
            'apartments' => ApartmentViewResource::collection($this->whenLoaded('apartments')),
            'photos' => $this->media->map(fn ($media) => $media->getUrl('thumbnail')),
            'avg_rating' => $this->bookings_avg_rating
        ];
    }
}
