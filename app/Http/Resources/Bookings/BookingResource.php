<?php

namespace App\Http\Resources\Bookings;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

/**
 * @OA\Schema(
 *     schema="BookingResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="apartment_name", type="string", example="Apartment 101"),
 *     @OA\Property(property="apartment_view_link", type="string", example="http://example.com/api/v1/apartments/view/1"),
 *     @OA\Property(property="start_date", type="string", format="date", example="2026-03-15"),
 *     @OA\Property(property="end_date", type="string", format="date", example="2026-03-20"),
 *     @OA\Property(property="adult_guests", type="integer", example=2),
 *     @OA\Property(property="children_guests", type="integer", example=1),
 *     @OA\Property(property="total_price", type="number", format="float", example=500.00),
 *     @OA\Property(property="rating", type="integer", example=9),
 *     @OA\Property(property="review_comment", type="string", example="Great stay!"),
 *     @OA\Property(property="booked_at", type="string", format="date", example="2026-03-01"),
 *     @OA\Property(property="cancelled_at", type="string", format="date", nullable=true, example=null)
 * )
 */
class BookingResource extends JsonResource
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
            'apartment_name' => $this->apartment->name,
            'apartment_view_link' => route('apartments.view', ['active_apartment' => $this->apartment]),
            'start_date' => $this->start_date->toDateString(),
            'end_date' => $this->end_date->toDateString(),
            'adult_guests' => $this->whenNotNull($this->adult_guests),
            'children_guests' => $this->whenNotNull($this->children_guests),
            'total_price' => $this->total_price,
            'rating' => $this->whenNotNull($this->rating),
            'review_comment' => $this->whenNotNull($this->review_comment),
            'booked_at' => $this->when(Gate::allows('manage-properties'), $this->created_at->toDateString()),
            'cancelled_at' => $this->whenNotNull($this->deleted_at?->toDateString())
        ];
    }
}
