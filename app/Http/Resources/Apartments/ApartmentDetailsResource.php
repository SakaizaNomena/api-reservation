<?php

namespace App\Http\Resources\Apartments;

use App\Http\Resources\Facilities\FacilityResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ApartmentDetailsResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Apartment 101"),
 *     @OA\Property(property="active", type="string", example="yes"),
 *     @OA\Property(property="type", type="string", example="Studio"),
 *     @OA\Property(property="adult_capacity", type="integer", example=2),
 *     @OA\Property(property="children_capacity", type="integer", example=1),
 *     @OA\Property(property="size", type="integer", example=50),
 *     @OA\Property(property="beds_list", type="string", example="1 double bed"),
 *     @OA\Property(property="bathrooms", type="integer", example=1),
 *     @OA\Property(property="facilities", type="array", @OA\Items(type="object")),
 *     @OA\Property(property="available_in", type="array", @OA\Items(type="object")),
 *     @OA\Property(property="booked_in", type="array", @OA\Items(type="object"))
 * )
 */
class ApartmentDetailsResource extends JsonResource
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
            'active' => $this->active ? 'yes' : 'no',
            'type' => $this->apartment_type->name,
            'adult_capacity' => $this->adult_capacity,
            'children_capacity' => $this->children_capacity,
            'size' => $this->size,
            'beds_list' => $this->beds_list,
            'bathrooms' => $this->bathrooms,
            'facilities' => FacilityResource::collection($this->whenLoaded('facilities')),
            'available_in' => $this->prices->map(function ($price) {
                return [
                    'id' => $price->id,
                    'from' => $price->start_date->toDateString(),
                    'to' => $price->end_date->toDateString(),
                    'price_per_night' => $price->price_per_night
                ];
            }),
            'booked_in' => $this->bookings->map(function ($booking) {
                return [
                    'from' => $booking->start_date->toDateString(),
                    'to' => $booking->end_date->toDateString(),
                    'total_price' => $booking->total_price
                ];
            }),
        ];
    }
}
