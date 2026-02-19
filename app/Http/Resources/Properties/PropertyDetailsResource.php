<?php

namespace App\Http\Resources\Properties;

use App\Http\Resources\Apartments\ApartmentDetailsResource;
use App\Http\Resources\Facilities\FacilityResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="PropertyDetailsResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Beautiful Villa"),
 *     @OA\Property(property="city", type="string", example="Paris"),
 *     @OA\Property(property="active", type="string", example="yes"),
 *     @OA\Property(property="address", type="string", example="123 Main St, 12345"),
 *     @OA\Property(property="bookings_avg_rating", type="string", example="8.5"),
 *     @OA\Property(property="apartments", type="array", @OA\Items(ref="#/components/schemas/ApartmentDetailsResource")),
 *     @OA\Property(property="apartments_view_link", type="string", example="http://example.com/api/v1/owner/properties/1/apartments"),
 *     @OA\Property(property="facilities", type="array", @OA\Items(type="object")),
 *     @OA\Property(property="photos", type="array", @OA\Items(ref="#/components/schemas/PropertyPhotoResource"))
 * )
 */
class PropertyDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'city' => $this->city->name,
            'active' => $this->active ? 'yes' : 'no',
            'address' => $this->address,
            'bookings_avg_rating' => $this->bookings_avg_rating ?? 'no average rating yet',
            'apartments' => ApartmentDetailsResource::collection($this->whenLoaded('apartments')),
            'apartments_view_link' => route('owner.apartments.index', ['property' => $this]),
            'facilities' => FacilityResource::collection($this->facilities),
            'photos' => PropertyPhotoResource::collection($this->getMedia('photos'))
        ];
    }
}
