<?php

namespace App\Http\Resources\Apartments;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ApartmentPriceResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="apartment_id", type="integer", example=1),
 *     @OA\Property(property="start_date", type="string", format="date", example="2026-03-01"),
 *     @OA\Property(property="end_date", type="string", format="date", example="2026-03-31"),
 *     @OA\Property(property="price_per_night", type="number", format="float", example=100.50)
 * )
 */
class ApartmentPriceResource extends JsonResource
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
            'apartment_id' => $this->apartment_id,
            'start_date' => $this->start_date->toDateString(),
            'end_date' => $this->end_date->toDateString(),
            'price_per_night' => $this->price_per_night,
        ];
    }
}
